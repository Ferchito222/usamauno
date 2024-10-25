import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import _ from 'underscore';
import moment from 'moment';
import { eLoading } from 'helpers';

import { PolicyEditSearchView } from './PolicyEditSearchView';
import { PaginationView } from './../../components/PaginationView';

export class PolicyEditListView extends Component {
  constructor(props) {
    super(props);
    this.state = {
      certificate: document.getElementById('r-c').value,
    };

    this.certificateUrl = this.certificateUrl.bind(this);
    this.edit = this.edit.bind(this);
  }

  certificateUrl = (code, rp_id, header_id) => {
    return this.state.certificate
                .replace(':code', code)
                .replace(':rp_id', rp_id)
                .replace(':header_id', header_id);
  }

  edit = (e, id, code) => {
    e.preventDefault();

    this.props.handleSetHeader(id);
    
    const { history } = this.props;
    const { formData } = this.state;

    history.push(`/edit/${code}/${id}`);
  }

  render() {
    const { formData } = this.state;
    const { lists, details, users, products, pagination, filter } = this.props;

    return (
      <div className="Grid-container">
        <div className="Grid-header">
          <h5>Edición de Certificados / Búsqueda</h5>
        </div>
        <div className="Grid-box2">
          <PolicyEditSearchView
            products={products}
            users={users}
            handleFilterData={this.props.handleFilterData}
            handleResetPagination={this.props.handleResetPagination}
          />
        </div>
        <div className="Grid-main">
          <table className="Table Table-hover Table-striped Table-mcTeal">
            <thead>
              <tr>
                <th>Nº de Certificado</th>
                <th>Cliente</th>
                <th>C.I.</th>
                <th>Monto Solicitado</th>
                <th>Usuario</th>
                <th>Fecha de Creación</th>
                <th>Emitido</th>
                <th>Anulado</th>
                <th>Facultativo</th>
                <th>Fecha de Emisión</th>
                <th></th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              {
                lists.map(header => {
                  return (
                    header.get('details').map(d => {
                      return (
                        <tr key={d}>
                          <td>{header.get('prefix')}-{header.get('issueNumber')}</td>
                          <td>{details.get(d).get('client').get('fullName')}</td>
                          <td>{details.get(d).get('client').get('dni')} {details.get(d).get('client').get('extension')}</td>
                          <td>{header.get('amountRequested').toLocaleString()} {header.get('currency')}</td>
                          <td>{header.get('userFullName')}</td>
                          <td>{moment(header.get('createdAt')).format('DD/MM/YYYY h:mm a')}</td>
                          <td>{header.get('issued') ? 'SI' : 'NO'}</td>
                          <td>{header.get('canceled') ? 'SI' : 'NO'}</td>
                          <td>{header.get('facultative') ? 'SI' : 'NO'}</td>
                          <td>{header.get('dateIssue') != null ? moment(header.get('dateIssue')).format('DD/MM/YYYY h:mm a') : ''}</td>
                          <td>
                            <a
                              className="Btn Btn-xs Btn-secundary"
                              href={this.certificateUrl(header.get('productCode'), header.get('rpIdx'), header.get('idx'))}
                              title="Ver Certificado"
                              target="_blank"
                            >
                              Ver Certificado
                            </a>
                          </td>
                          <td>
                            <a href="#" className="Btn Btn-xs Btn-success" title="Editar" onClick={(e) => this.edit(e, header.get('idx'), header.get('productCode'))}>
                              Editar
                            </a>
                          </td>
                        </tr>
                      )
                    })
                  )
                })
              }
            </tbody>
          </table>

          {
            ! filter &&
            <PaginationView
              pagination={pagination}
              handleFetchData={this.props.handleFetchData}
            />
          }
        </div>
      </div>
    )
  }
}