import React from 'react';

export const UserListView = (props) => {
  const { lists, userType, products, companies } = props;

  return (
    <table className="Table Table-hover Table-striped Table-mcTeal">
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Departamento / Agencia</th>
          <th>Perfil de Usuario</th>
          <th>Productos</th>
          <th>Permisos</th>
          <th>Estado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        {
          lists.map((user, index) => {
            return (
              <tr key={index}>
                <td style={{ fontWeight: 600 }}>{user.get('userName')}</td>
                <td>{user.get('fullName')}</td>
                <td>{user.get('email')}</td>
                <td>
                  {user.get('cityName')}
                  <br />
                  {user.get('agencyName')}
                </td>
                <td>
                  {
                    user.get('profileSlug') === 'COP' ? (
                      <div className="Wrapper-dropdown" onClick={props.toggle}>
                        {user.get('profileName')} <i className="material-icons">arrow_drop_down_circle</i>
                        <ul className="Dropdown">
                          {
                            user.get('companies').map((company, index) => {
                              return (
                                <li key="index">
                                  <a href="#">
                                    <i className="material-icons">view_list</i>
                                    {companies[company]}
                                  </a>
                                </li>
                              )
                            })
                          }
                        </ul>
                      </div>
                    ) : (
                      <span>{user.get('profileName')}</span>
                    )
                  }
                </td>
                <td>
                  <div className="Wrapper-dropdown" onClick={props.toggle}>
                    Productos <i className="material-icons">arrow_drop_down_circle</i>
                    <ul className="Dropdown">
                      {
                        user.get('products').map((product, index) => {
                          return (
                            <li key={index}>
                              <a href="#">
                                <i className="material-icons">view_list</i>
                                {products[product]}
                              </a>
                            </li>
                          )
                        })
                      }
                    </ul>
                  </div>
                </td>
                <td>
                  <div className="Wrapper-dropdown" onClick={props.toggle}>
                    Permisos <i className="material-icons">arrow_drop_down_circle</i>
                    <ul className="Dropdown">
                      {
                        user.get('permissionNames').map((permission, index) => {
                          return (
                            <li key={index}>
                              <a href="#">
                                <i className="material-icons">view_list</i>
                                {permission}
                              </a>
                            </li>
                          )
                        })
                      }
                    </ul>
                  </div>
                </td>
                <td>
                  <span className={`Badge ${user.get('active') ? 'Badge-success' : 'Badge-danger'}`}>
                    {user.get('active') ? 'Habilitado' : 'Deshabilitado'}
                    <br />
                    <i className="material-icons">{user.get('passwordChange') ? 'lock_open' : 'lock'}</i>
                  </span>
                </td>
                <td>
                  <ul className="Action-dropdown">
                    <li className="Action-dropdownTop">
                      <a href="#">
                        <i className="material-icons">more_vert</i>
                      </a>
                      <span></span>
                      <ul className="Action-dropdownBox">
                        <li>
                          <a href="#Modal-app" onClick={() => props.setView('UserFormView', user.get('idx'))}>
                            Editar Perfil<i className="material-icons">border_color</i>
                          </a>
                        </li>
                        <li>
                          <a href="#Modal-app" onClick={() => props.setView('PasswordView', user.get('idx'))}>
                            Cambiar contraseña<i className="material-icons">lock</i>
                          </a>
                        </li>
                        <li>
                          <a href="#Modal-app" onClick={() => props.setView('PasswordView', user.get('idx'), true)}>
                            Resetear contraseña<i className="material-icons">lock</i>
                          </a>
                        </li>
                        <li>
                          <a href="#" onClick={(e) => props.handleUserActive(e, user)}>
                            {user.get('active') ? 'Deshabilitar' : 'Habilitar'}
                            <i className="material-icons">{user.get('active') ? 'cancel' : 'check_circle'}</i>
                          </a>
                        </li>
                        <li>
                          {
                            userType === 'ADT' && (
                              <a href="#" onClick={(e) => props.handlePasswordTurnOff(e, user)}>
                                {user.get('passwordChange') ? 'Desactivar' : 'Activar'} cambio de contraseña
                                <i className="material-icons">lock_open</i>
                              </a>
                            )
                          }
                        </li>
                      </ul>
                    </li>
                  </ul>
                </td>
              </tr>
            )
          })
        }
      </tbody>
    </table>
  )
}