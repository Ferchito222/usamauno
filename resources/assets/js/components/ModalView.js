import React from 'react';

const ModalView = (props) => {
  return (
    <div className="Modal-dialog" id="Modal-app">
      <div className="Modal-content">
        <a className="Modal-close" id="Modal-close" href="#close" title="Cerrar" onClick={props.handleClose}>
          X
        </a>
        <div className="Modal-body">
          {props.children}
        </div>
      </div>
    </div>
  )
}

export default ModalView;