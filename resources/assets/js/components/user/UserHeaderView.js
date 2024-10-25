import React, { Fragment } from 'react';

export const UserHeaderView = (props) => {
  const { query, userType, exportUrl } = props;
  const $urlImport = document.getElementById('ru-import').value;

  return (
    <Fragment>
      <div className="Grid-header">
        <h5>Usuarios / Lista de Usuarios</h5>
      </div>
  
      <div className="Grid-widget" style={{ paddingLeft: '8px' }}>
        <input
          type="search"
          name="query"
          id="Box-search"
          autoComplete="off"
          placeholder="Buscar por usuario, nombre, email o perfil"
          value={query}
          onChange={props.handleInputChange}
        />
      </div>
  
      <div className="Grid-action">
        <a className="Btn Btn-sm Btn-primary" href="#Modal-app" onClick={() => props.handleSetView('UserFormView')}>
          Agregar <i className="material-icons">add</i>
        </a>
        {
          userType === 'ADT' && (
            <a className="Btn Btn-icon Btn-danger Btn-spaceLeft" href={$urlImport} title="Importar Usuarios">
              <i className="material-icons">cloud_upload</i>
            </a>
          )
        }
        <a className="Btn Btn-icon Btn-success Btn-spaceLeft" href={exportUrl} title="Descargar Usuarios" target="_blank">
          <i className="material-icons">cloud_download</i>
        </a>
      </div>
    </Fragment>
  )
};