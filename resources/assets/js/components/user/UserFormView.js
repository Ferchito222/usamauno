import React, { PureComponent } from 'react';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import _ from 'underscore';
import { eLoading } from 'helpers';

import PasswordStrength from './PasswordStrength';

import './css/User.css';

export class UserFormView extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {
      formData: this.initialState,
      errors: {},
      boxProfiles: false,
      boxCompanies: false,
      permissions: [],
      agencies: [],
      passwordIsValid: false,
    };

    this.setProfileAndPermissions = this.setProfileAndPermissions.bind(this);
    this.setCompanies = this.setCompanies.bind(this);
    this.setAgencies = this.setAgencies.bind(this);
    this.userSetUp = this.userSetUp.bind(this);
  }

  get initialState () {
    const { data, user } = this.props;

    let retailer = _.keys(data.retailer)[0];
    let products = [];
    let userType = _.first(data.user_types).id;
    let profile = _.first(data.user_profiles).id;
    let companies = [];
    let permissions = [];
    let username = '';
    let password = '';
    let password_confirmation = '';
    let email = '';
    let fullName = '';
    let city = '';
    let agency = '';
    let additionalAgencies = [];
    let phoneNumber = '';
    let position = '';

    if (user) {
      retailer = user.get('retailer');
      products = user.get('products').toJS();
      userType = user.get('type');
      profile = user.get('profileIdx');
      companies = user.get('companies').toJS();
      permissions = user.get('permissionIdx').toJS();
      username = user.get('userName');
      email = user.get('email');
      fullName = user.get('fullName');
      city = user.get('cityIdx');
      additionalAgencies = user.get('additionalAgencies').toJS();
      phoneNumber = user.get('phoneNumber');
      position = user.get('position');
      
      if (user.get('agencyIdx')) {
        agency = user.get('agencyIdx');
      }
    }

    return {
      retailer,
      products,
      userType,
      profile,
      companies,
      permissions,
      username,
      password,
      password_confirmation,
      email,
      fullName,
      city,
      agency,
      additionalAgencies,
      phoneNumber,
      position,
    }
  }

  componentDidMount = () => {
    const { userType, profile, city } = this.state.formData;

    if (this.props.user) {
      this.setCompanies(profile);
      this.setAgencies(city);
      
      this.setState({ passwordIsValid: true });
    }
    
    this.setProfileAndPermissions(userType);
  }

  componentDidUpdate(prevProps, prevState) {
    if (this.state.formData.userType !== prevState.formData.userType) {
      this.setProfileAndPermissions(this.state.formData.userType);
    }

    if (this.state.formData.profile !== prevState.formData.profile) {
      this.setCompanies(this.state.formData.profile);
    }

    if (this.state.formData.city !== prevState.formData.city) {
      this.setAgencies(this.state.formData.city, prevState);
    }
  }

  handleInputChange = (event) => {
    const target = event.target;
    const name = target.name;
    let value = target.type === 'checkbox' ? target.checked : target.value;
    
    if (target.multiple) {
      value = [].slice.call(target.selectedOptions).map(o => o.value);
    }
    
    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        [name]: value,
      }
    }));
  }

  handlePassword = (state) => {
    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        password: state.password,
      },
      passwordIsValid: state.isValid,
    }));
  }

  setProfileAndPermissions = (userType) => {
    let _userType = this.props.data.user_types.filter(item => (item.id == userType));
    let boxProfiles = false
    let permissions = [];

    if (_userType[0].code === 'UST') {
      boxProfiles = true;

      permissions = this.props.data.user_permissions.filter(item => (item.codename != 'admin'));
      permissions = _.pluck(permissions, 'id');
    } else {
      permissions = this.props.data.user_permissions.filter(item => (item.codename == 'admin'));
      permissions = _.pluck(permissions, 'id');
    }

    this.setState({ boxProfiles, permissions });
  }

  setCompanies = (profile) => {
    let userProfile = this.props.data.user_profiles.filter(item => (item.id == profile));
    let boxCompanies = false;

    if (userProfile[0].slug === 'COP') {
      boxCompanies = true;
    }

    this.setState({ boxCompanies });
  }

  setAgencies = (city, prevState) => {
    let _city = this.props.data.cities.filter(item => (item.id == city));
    const agencies = this.props.data.agencies.filter(item => (item.city_slug == _city[0].slug));

    if (! this.props.user) {
      let agency_val = _.first(agencies);

      if (typeof agency_val != 'undefined') {
        this.setState(prevState => ({
          formData: {
            ...prevState.formData,
            agency: agency_val.id,
          }
        }));
      }
    }

    this.setState({ agencies });
  }

  userSetUp = (e) => {
    e.preventDefault();
    
    eLoading(true);

    let $url = document.getElementById('ru-create').value;
    let data = this.state.formData;
    const { user } = this.props;

    if (user) {
      data = {
        ...data,
        id: user.get('idx')
      };

      $url = document.getElementById('ru-edit').value;

      axios.put($url, data)
        .then(response => {
          if (response.status === 200) {
            const data = response.data;

            if (data.success) {
              this.props.handleUserSetUp(data.user, true);
              
              NotificationManager.success(data.message, 'Mensaje importante');
            }
          }

          eLoading(false);
        })
        .catch(e => {
          eLoading(false);

          if (e.response) {
            this.setState({ errors: e.response.data })

            if (e.response.data.warning) {
              NotificationManager.warning(e.response.data.message, 'Mensaje importante');
            }
          } else if (e.request) {
            // console.log(e.request);
          } else {
            // console.log('Error', e.message);
          }
        });
    } else {
      axios.post($url, data)
        .then(response => {
          if (response.status === 200) {
            const data = response.data;

            if (data.success) {
              this.props.handleUserSetUp(data.user);

              NotificationManager.success(data.message, 'Mensaje importante');
            }
          }

          eLoading(false);
        })
        .catch(e => {
          eLoading(false);

          if (e.response) {
            this.setState({ errors: e.response.data })

            if (e.response.data.warning) {
              NotificationManager.warning(e.response.data.message, 'Mensaje importante');
            }
          } else if (e.request) {
            // console.log(e.request);
          } else {
            // console.log('Error', e.message);
          }
        });
    }
  }

  render() {
    const { formData, errors, boxProfiles, boxCompanies, permissions, agencies, passwordIsValid } = this.state;
    const { data, user, userType, minLength, maxLength } = this.props;
    // console.log(data.userType);
    

    return (
      <div>
        <h4 className="Modal-title">{! user ? 'Nuevo' : 'Editar'} Usuario</h4>

        <form onSubmit={this.userSetUp}>
          <fieldset>
            <div className="Box">
              <div className="Box-col6">
                <label>Retailer:</label>
                <select name="retailer" value={formData.retailer} onChange={this.handleInputChange}>
                  {
                    _.map(data.retailer, (value, index) => 
                      <option value={index} key={index}>{value}</option>
                    )
                  }
                </select>
                {errors.retailer && <div className="Text-error">{errors.retailer[0]}</div>}

                <label>Productos:</label>
                <select name="products" value={formData.products} onChange={this.handleInputChange} multiple={true} className="products">
                  {
                    _.map(data.products, (value, index) =>
                      <option value={index} key={index}>{value}</option>
                    )
                  }
                </select>
                {errors.products && <div className="Text-error">{errors.products[0]}</div>}

                <label>Tipo de Usuario:</label>
                <select name="userType" value={formData.userType} onChange={this.handleInputChange}>
                  {
                    data.user_types.map(type =>
                      <option value={type.id} key={type.id}>{type.name}</option>
                    )
                  }
                </select>
                {errors.userType && <div className="Text-error">{errors.userType[0]}</div>}

                {
                  boxProfiles &&
                  <div>
                    <label>Perfil:</label>
                    <select name="profile" value={formData.profile} onChange={this.handleInputChange}>
                      {
                        data.user_profiles.map(profile => 
                          <option value={profile.id} key={profile.id}>{profile.name}</option>
                        )
                      }
                    </select>
                    {errors.profile && <div className="Text-error">{errors.profile[0]}</div>}
                  </div>
                }

                {
                  boxCompanies &&
                  <div>
                    <label>Compañias:</label>
                    <select name="companies" value={formData.companies} onChange={this.handleInputChange} multiple="multiple">
                      {
                        _.map(data.companies, (value, index) =>
                          <option value={index} key={index}>{value}</option>
                        )
                      }
                    </select>
                    {errors.companies && <div className="Text-error">{errors.companies[0]}</div>}
                  </div>
                }

                <label>Permisos:</label>
                <select name="permissions" value={formData.permissions} onChange={this.handleInputChange} multiple={true} className="permissions">
                  {
                    data.user_permissions.map(permission => 
                      permissions.includes(permission.id) && 
                      <option value={permission.id} key={permission.id}>{permission.name}</option>
                    )
                  }
                </select>
                {errors.permissions && <div className="Text-error">{errors.permissions[0]}</div>}                
              </div>
              <div className="Box-col6">
                <label>Nombre de Usuario:</label>
                <input type="text" name="username" value={formData.username} onChange={this.handleInputChange} autoComplete="off" />
                {errors.username && <div className="Text-error">{errors.username[0]}</div>}

                {
                  (! user) && (
                    <div>
                      <label>Contraseña:</label>
                      <PasswordStrength
                        inputProps={{ name: 'password', autoComplete: 'off' }}
                        minLength={minLength}
                        maxLength={maxLength}
                        changeCallback={this.handlePassword}
                      />
                      {errors.password && <div className="Text-error">{errors.password[0]}</div>}
  
                      <label>Confirmar contraseña:</label>
                      <input type="password" name="password_confirmation" value={formData.password_confirmation} onChange={this.handleInputChange} autoComplete="off" />
                    </div>
                  )
                }
                
                <label>Email:</label>
                <input type="email" name="email" value={formData.email} onChange={this.handleInputChange} autoComplete="off" />
                {errors.email && <div className="Text-error">{errors.email[0]}</div>}
                
                <label>Nombres y Apellidos:</label>
                <input type="text" name="fullName" value={formData.fullName} onChange={this.handleInputChange} autoComplete="off" />
                {errors.fullName && <div className="Text-error">{errors.fullName[0]}</div>}
                
                <label>Departamento:</label>
                <select name="city" value={formData.city} onChange={this.handleInputChange}>
                  <option value="">Seleccione...</option>
                  {
                    data.cities.map(city =>
                      <option value={city.id} key={city.id}>{city.name}</option>
                    )
                  }
                </select>
                {errors.city && <div className="Text-error">{errors.city[0]}</div>}
                
                <label>Agencia:</label>
                <select name="agency" value={formData.agency} onChange={this.handleInputChange}>
                  {
                    agencies.map(agency =>
                      <option value={agency.id} key={agency.id}>{agency.name}</option>
                    )
                  }
                </select>
                {errors.agency && <div className="Text-error">{errors.agency[0]}</div>}

                <label>Agencias Adicionales:</label>
                <select name="additionalAgencies" value={formData.additionalAgencies} onChange={this.handleInputChange} multiple={true} className="additionalAgencies">
                  {
                    agencies.map(agency =>
                      <option value={agency.id} key={agency.id}>{agency.name}</option>
                    )
                  }
                  {errors.additionalAgencies && <div className="Text-error">{errors.additionalAgencies[0]}</div>}
                </select>

                <label>Teléfono de agencia:</label>
                <input type="text" name="phoneNumber" value={formData.phoneNumber} onChange={this.handleInputChange} autoComplete="off" />
                {errors.phoneNumber && <div className="Text-error">{errors.phoneNumber[0]}</div>}
                                
                <label>Cargo:</label>
                <input type="text" name="position" value={formData.position} onChange={this.handleInputChange} autoComplete="off" />
                {errors.position && <div className="Text-error">{errors.position[0]}</div>}
              </div>
            </div>
            <div className="Box">
              <div className="Box-col" style={{ textAlign: 'center' }}>
                <button className="Btn Btn-sm Btn-success" type="submit" disabled={!passwordIsValid}>Guardar</button>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    )
  }
}