import React from 'react';
import _ from 'underscore';

export const CancellationSearchView = (props) => {
  const { formData, data } = props;

  return (
    <form onSubmit={(e) => { e.preventDefault() }}>
      <fieldset>
        <div className="Box">
          <div className="Box-col4">
            <label>Producto:</label>
            <select name="product" value={formData.product} onChange={props.handleInputChange}>
              {
                _.map(data.products, (p, index) => 
                  <option value={index} key={index}>{p}</option>
                )
              }
            </select>
          </div>
          <div className="Box-col4">
            <label>Nro. de Certificado:</label>
            <input type="text" name="policy_number" value={formData.policy_number} autoComplete="off" onChange={props.handleInputChange} />
          </div>
        </div>
        <div className="Box">
          <div className="Box-col4">
            <label>Sucursal:</label>
            <select name="city" value={formData.city} onChange={props.handleInputChange}>
              {
                data.cities.map(city =>
                  <option value={city.id} key={city.id}>{city.name}</option>
                )
              }
            </select>

            <label>Cliente:</label>
            <input type="text" name="client" value={formData.client} autoComplete="off" onChange={props.handleInputChange} />
          </div>
          <div className="Box-col4">
            <label>Agencia:</label>
            <select name="agency" value={formData.agency} onChange={props.handleInputChange}>
              {
                data.agencies.map(agency =>
                  <option value={agency.id} key={agency.id}>{agency.name}</option>
                )
              }
            </select>

            <label>C.I.:</label>
            <input type="text" name="dni" value={formData.dni} autoComplete="off" onChange={props.handleInputChange} />
          </div>
          <div className="Box-col4">
            <label>Usuario:</label>
            <select name="username" value={formData.username} onChange={props.handleInputChange}>
              {
                data.users.map(username =>
                  <option value={username.id} key={username.id}>{username.name}</option>
                )
              }
            </select>

            <label>Fecha (Desde - Hasta):</label>
            <input
              type="date"
              name="date_begin"
              value={formData.date_begin}
              onChange={props.handleInputChange}
              className="Form-date"
              placeholder="Desde"
              style={{ width: '49%' }}
            />
            <input type="date"
              name="date_end"
              value={formData.date_end}
              onChange={props.handleInputChange}
              className="Form-date"
              placeholder="Hasta"
              style={{ width: '49%', marginLeft: '2%' }}
            />
          </div>
        </div>
        <div className="Box">
          <div className="Box-col" style={{ textAlign: 'center' }}>
            <button className="Btn Btn-icon-sm Btn-secundary" type="button" onClick={props.handleReset}>
              <i className="material-icons">settings_backup_restore</i>
            </button>

            <button className="Btn Btn-icon-sm Btn-info Btn-spaceLeft" type="submit" onClick={(e) => { e.preventDefault(); props.handleSearch(null); }}>
              <i className="material-icons">search</i>
            </button>
          </div>
        </div>
      </fieldset>
    </form>
  )
}