import React, { PureComponent } from 'react';
import { NotificationManager } from 'react-notifications';
import Select from 'react-select';
import { eLoading } from 'helpers';

import 'react-select/dist/react-select.css';

const placeholder = 'Seleccionar...';
const noResultsText = 'No se encontraron resultados';

export class AssignmentWalletView extends PureComponent {
  constructor(props) {
    super(props);
    this.state = this.initialState;
    
    this.handleUser = this.handleUser.bind(this);
    this.handleUserTo = this.handleUserTo.bind(this);
    this.handleInputChange = this.handleInputChange.bind(this);
    this.store = this.store.bind(this);
  }

  get initialState () {
    return {
      userFrom: '',
      userTo: '',
      certificates: [],
      disabled: false,
      userFullName: '',
      errors: {},
    }
  }

  handleUser = (user) => {
    this.setState({
      userFrom: (user ? user.id : ''),
      userFullName: (user ? user.fullName : ''),
    });

    this.props.handleCertificates(user);
  }

  handleUserTo = (user) => {
    this.setState({ userTo: (user ? user.id : '') });
  }

  handleInputChange = (event) => {
    const target = event.target;
    const name = target.name;
    let value = target.type === 'checkbox' ? target.checked : target.value;

    if (target.multiple) {
      value = [].slice.call(target.selectedOptions).map(o => o.value);
    }

    this.setState({
      [name]: value,
    });
  }

  store = (e) => {
    e.preventDefault();
    eLoading(true);

    const $url = document.getElementById('roa-wallet').value;
    const { userFrom, userTo, certificates, disabled } = this.state;

    axios.post($url, { userFrom, userTo, certificates, disabled })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const data = response.data;

          if (data.success) {
            this.props.handleAssignment(userFrom, userTo, certificates);
            this.setState({ ...this.initialState });

            NotificationManager.success(data.message, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        eLoading(false);

        if (e.response) {
          this.setState({ errors: e.response.data });

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

  render() {
    const { userFrom, userTo, certificates, disabled, errors, userFullName } = this.state;
    const { usersFrom, usersTo, headers, records } = this.props;
    
    return (
      <div className="Grid-container">
        <div className="Grid-header">
          <h5>Asignación de cartera</h5>
        </div>
        <div className="Grid-box">
          <form onSubmit={this.store}>
            <fieldset>
              <div className="Box">
                <div className="Box-col">
                  <label style={styles.label}>Del Usuario</label>
                  <Select
                    name="userFrom"
                    value={userFrom}
                    labelKey="fullName"
                    valueKey="id"
                    placeholder={placeholder}
                    noResultsText={noResultsText}
                    onChange={this.handleUser}
                    options={usersFrom.toJS()}
                  />
                  {errors.userFrom && <div className="Text-error" style={styles.textError}>{errors.userFrom[0]}</div>}

                  <label style={styles.label}>Al Usuario</label>
                  <Select
                    name="userTo"
                    value={userTo}
                    labelKey="fullName"
                    valueKey="id"
                    placeholder={placeholder}
                    noResultsText={noResultsText}
                    onChange={this.handleUserTo}
                    options={usersTo.toJS()}
                  />
                  {errors.userTo && <div className="Text-error" style={styles.textError}>{errors.userTo[0]}</div>}

                  <label style={styles.label}>Certificados</label>
                  <select name="certificates" value={certificates} onChange={this.handleInputChange} multiple={true} style={styles.certificates}>
                    {
                      records.map(r => {
                        let header = headers.get(r);

                        return (
                          <option value={r} key={r}>
                            {header.get('prefix')} - {header.get('issueNumber')} / {header.get('clients').join(' - ')}
                          </option>
                        )
                      })
                    }
                  </select>
                  {errors.certificates && <div className="Text-error">{errors.certificates[0]}</div>}

                  <label className="Control Control-checkbox">
                    Deshabilitar al usuario: <strong>{userFullName}</strong>
                    <input type="checkbox" name="disabled" value={disabled} onChange={this.handleInputChange} />
                    <div className="Control-indicator"></div>
                  </label>

                  <button className="Btn Btn-sm Btn-success" type="submit" style={styles.btn}>Traspasar Cartera</button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    )
  }
}

const styles = {
  label: {
    marginTop: '20px',
    fontSize: '.9rem',
  },
  certificates: {
    height: '250px',
    overflow: 'auto',
  },
  textError: {
    marginTop: '1px',
  },
  btn: {
    marginTop: '10px',
  }
}