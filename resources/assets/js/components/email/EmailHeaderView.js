import React, { Fragment } from 'react';

export const EmailHeaderView = (props) => {
  return (
    <Fragment>
    <div className="Grid-header">
      <h5>Correos Electrónicos</h5>
    </div>
    <div className="Grid-action">
      <a className="Btn Btn-primary" href="#Modal-app" onClick={() => props.setView('EmailFormView')}>
        Agregar <i className="material-icons">add</i>
      </a>
    </div>
    </Fragment>
  )
}