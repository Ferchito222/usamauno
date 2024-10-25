import React from 'react';
import moment from 'moment';

export const AttachedCertificateListView = (props) => {
  const { lists, details } = props;
  const $url = document.getElementById('roAc-attached').value;

  return (
    <table className="Table Table-hover Table-striped Table-mcTeal">
      <thead>
        <tr>
          <th>Nº de Certificado</th>
          <th>Cliente</th>
          <th>Usuario</th>
          <th>Fecha de Emisión</th>
          <th></th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        {
          lists.map((header, index) => 
            header.get('details').map(d => {
              return (
                <tr key={d}>
                  <td>{header.get('prefix')}-{header.get('issueNumber')}</td>
                  <td>{details.get(d.toString()).get('clientFullName')}</td>
                  <td>{header.get('userFullName')}</td>
                  <td>{moment(header.get('dateIssue')).format('DD/MM/YYYY h:mm a')}</td>
                  <td>
                    <a
                      href={$url.replace(':file', header.get('file'))}
                      title="Ver Certificado Adjunto"
                      target="_blank"
                      className="Btn-xs Btn-secundary"
                    >
                      Ver Certificado Adjunto
                    </a>
                  </td>
                  <td>
                    <a
                      href="#!"
                      title="Eliminar Certificado Adjunto"
                      className="Btn Btn-icon-sm Btn-danger"
                      onClick={(e) => props.handleAttachedDelete(e, header.get('idx'))}>
                      <i className="material-icons">delete_forever</i>
                    </a>
                  </td>
                </tr>
              )
            })
          )
        }
      </tbody>
    </table>
  )
}