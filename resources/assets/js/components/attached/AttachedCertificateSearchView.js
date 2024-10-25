import React from 'react'

export const AttachedCertificateSearchView = (props) => {
  const { formData } = props;
  
  return (
    <form onSubmit={(e) => {e.preventDefault()}}>
      <fieldset>
        <div className="Box">
          <div className="Box-col6">
            <label>Nº de Certificado:</label>
            <input type="text" name="number" value={formData.number} autoComplete="off" onChange={props.handleInputChange} />
            
            <label>Usuario:</label>
            <input type="text" name="user" value={formData.user} autoComplete="off" onChange={props.handleInputChange} />
          </div>
          <div className="Box-col6">
            <label>Cliente:</label>
            <input type="text" name="client" value={formData.client} autoComplete="off" onChange={props.handleInputChange} />

            <label>Fecha de Emisión (Desde - Hasta):</label>
            <input
              type="date"
              name="dateBegin"
              value={formData.dateBegin}
              onChange={props.handleInputChange}
              className="Form-date"
              placeholder="Desde"
              style={{ width: '49%' }}
            />
            <input type="date"
              name="dateEnd"
              value={formData.dateEnd}
              onChange={props.handleInputChange}
              className="Form-date"
              placeholder="Hast"
              style={{ width: '49%', marginLeft: '2%' }}
            />
          </div>
        </div>
        <div className="Box">
          <div className="Box-col" style={{textAlign: 'center'}}>
            <button className="Btn Btn-icon-sm Btn-secundary" type="button" onClick={props.handleResetForm}>
              <i className="material-icons">settings_backup_restore</i>
            </button>
            
            <button className="Btn Btn-icon-sm Btn-info Btn-spaceLeft" type="button" onClick={props.handleGetData}>
              <i className="material-icons">refresh</i>
            </button>
          </div>
        </div>
      </fieldset>
    </form>
  )
}