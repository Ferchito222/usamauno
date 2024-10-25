import React from 'react';
import _ from 'underscore';
import numeral from 'numeral';
import moment from 'moment';

export const CancellationListView = (props) => {
  const { headers, details, data, product, certificate, } = props;

  return (
    <table id="data-Table" className="Table Table-hover Table-striped Table-mcTeal">
      <thead>
        <tr>
          <th>Nro. de Certificado</th>
          <th>Cliente</th>
          <th>C.I.</th>
          <th>Monto Solicitado</th>
          <th>Plazo del Crédito</th>
          <th>Usuario</th>
          <th>Sucursal / Agencia</th>
          <th>Fecha de Ingreso</th>
          <th>Certificados Anulados</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        {
          headers.map(header => 
            header.get('details').map(d =>
              <tr key={d}>
                <td><strong>{header.get('prefix')}-{header.get('issueNumber')}</strong></td>
                <td>{details.get(d.toString()).get('clientFullName')}</td>
                <td>{details.get(d.toString()).get('clientDni')} {details.get(d.toString()).get('clientExtension')}</td>
                <td>{numeral(header.get('amountRequested')).format('0,0.00')} {header.get('currency')}</td>
                <td>{header.get('term')} {!_.isEmpty(data) ? data.type_terms[header.get('typeTerm')] : ''}</td>
                <td>{header.get('userFullName')}</td>
                <td>
                  {header.get('userCityName')}
                  <br />
                  {header.get('userAgencyName')}
                </td>
                <td>{moment(header.get('createdAt').get('date')).format('DD/MM/YYYY h:mm a')}</td>
                <td>{'NO'}</td>
                <td>
                  <ul className="Action-dropdown">
                    <li className="Action-dropdownTop">
                      <a href="#">
                        <i className="material-icons">more_vert</i>
                      </a>
                      <span></span>
                      <ul className="Action-dropdownBox">
                        <li>
                          <a href="#Modal-app" onClick={() => props.handleSetHeader('CancellationFormView', header.get('idx'))}>
                            Anular<i className="material-icons">cancel</i>
                          </a>
                        </li>
                        <li>
                          <a href={certificate.replace(':rp_id', product).replace(':header_id', header.get('idx'))} target="_blank">
                            Ver Certificado de Emisión<i className="material-icons">dock</i>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </td>
              </tr>
            )
          )
        }
      </tbody>
    </table>
  )
}