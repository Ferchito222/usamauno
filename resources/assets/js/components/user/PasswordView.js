import React, { PureComponent } from 'react';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import { eLoading } from 'helpers';

import PasswordStrength from './PasswordStrength';

export default class PasswordView extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {
      formData: {
        id: null,
        oldPassword: '',
        password: '',
        password_confirmation: '',
      },
      errors: {},
    };
  }

  componentDidMount = () => {
    const { user } = this.props;

    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        id: user.get('idx'),
      }
    }));
  }

  handleInputChange = (event) => {
    const target = event.target;
    const name = target.name;
    let value = target.type === 'checkbox' ? target.checked : target.value;

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

  passwordEdit = (e) => {
    e.preventDefault();
    eLoading(true);

    const $url = document.getElementById('ru-password-update').value;
    let data = this.state.formData;

    if (this.props.reset) {
      data.reset = true;
    }

    axios.put($url, data)
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          let data = response.data;

          if (data.success) {
            this.props.handlePasswordEdit(this.state.formData.id);
            
            NotificationManager.success(data.message, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        eLoading(false);

        if (e.response) {
          this.setState({ errors: e.response.data });
        } else if (e.request) {
          // console.log(e.request);
        } else {
          // console.log('Error', e.message);
        }
      });
  }

  render() {
    const { formData, errors } = this.state;
    const { user, reset, minLength, maxLength } = this.props;

    return (
      <div>
        <h4 className="Modal-title">{! reset ? 'Cambiar' : 'Resetear'} contraseña</h4>

        <form style={{ width: '400px' }} onSubmit={this.passwordEdit}>
          <fieldset>
            <div className="Box">
              <div className="Box-col">
                <label style={{ marginBottom: '10px' }}>
                  Nombre de Usuario: <br />
                  <strong>{user.get('userName')} ({user.get('fullName')})</strong>
                </label>

                {
                  ! reset &&
                  <div>
                    <label>Contraseña actual:</label>
                    <input type="password" name="oldPassword" value={formData.oldPassword} onChange={this.handleInputChange} autoComplete="off" />
                    {errors.oldPassword && <div className="Text-error">{errors.oldPassword[0]}</div>}
                  </div>
                }

                <label>Nueva Contraseña:</label>
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
            </div>
            <div className="Box">
              <div className="Box-col" style={{ textAlign: 'center' }}>
                <button className="Btn Btn-sm Btn-success" type="submit">Guardar</button>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    )
  }
}
