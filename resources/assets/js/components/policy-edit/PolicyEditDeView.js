import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import ReactJson from 'react-json-view';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import moment from 'moment';
import _ from 'underscore';
import { eLoading } from 'helpers';

export class PolicyEditDeView extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {},
      editHeader: true,
      editClient: true,
      log: null,
      certificate: document.getElementById('r-c').value,
      header: {},
    };

    this.setHeader = this.setHeader.bind(this);
    this.getData = this.getData.bind(this);
    this.handleChange = this.handleChange.bind(this);
    this.handleUpdate = this.handleUpdate.bind(this);
  }
  
  componentDidMount = () => {
    const { header } = this.props;

    if (_.isEmpty(header)) {
      const { match } = this.props;

      this.props.handleSetHeader(match.params.id);
    } else {
      this.setHeader(header);
    }

    this.getData();
  }

  componentDidUpdate = (prevProps, prevState) => {
    if (this.props.header !== prevProps.header) {
      this.setHeader(this.props.header);
    }
  }

  setHeader = (header) => {
    this.setState({ header })
  }

  getData = () => {
    eLoading(true);

    const $url = document.getElementById('r-ope-e').value.replace(':code', 'de');

    axios.get($url)
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const { data } = response.data;

          this.setState({
            data,
          });
        }
      })
      .catch(e => {
        // console.info(e);
      });
  }

  handleChange = (event, index = -1, type = '') => {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    if (index >= 0) {
      this.setState(prevState => ({
        header: {
          ...prevState.header,
          details: prevState.header.details.map((d, idx) => {
            if (idx !== index) return d;

            if (type === 'c') {
              return {
                ...d,
                client: {
                  ...d.client,
                  [name]: value,
                }
              }
            } else if (type === 'd') {
              return {
                ...d,
                [name]: value,
              }
            }
          }),
        }
      }));
    } else {
      this.setState(prevState => ({
        header: {
          ...prevState.header,
          [name]: value,
        }
      }));
    }
  }

  handleUpdate = (e, type) => {
    e.preventDefault();
    eLoading(true);
    
    const { header } = this.state;

    const { idx } = header;
    const $url = document.getElementById('r-ope-u').value.replace(':code', 'de').replace(':id', idx);
    const formData = new FormData(e.target);
    const data = {};
    
    for (const [key, value] of formData.entries()) {
      data[key] = value;
    }

    axios.put($url, {
      type,
      data,
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const data = response.data;

          if (data.success) {
            this.setState({
              [`edit${type}`]: true,
            });

            this.props.handleUpdateHeader(data.api);
            this.props.handleSetHeader(data.api.header.idx);

            NotificationManager.success(data.message, 'Mensaje importante');
          }

          if (data.log) {
            this.setState({ log: data.log });
          }
        }
      })
      .catch(e => {
        eLoading(false);
        
        this.setState({ log: null });

        if (e.response) {
          if (e.response.data.warning) {
            NotificationManager.warning(e.response.data.message, 'Mensaje importante');
          }
        } else if (e.request) {
        } else {
          // console.log('Error', e.message);
        }
      });
  }

  render() {
    const { header, data, log, certificate, editHeader, editClient } = this.state;

    return (
      <div>
        {
          !_.isEmpty(header) && !_.isEmpty(data) > 0 && 
          <div className="Grid-container" v-if="header != null">
            <div className="Grid-header">
              <h5>Edición de Certificados</h5>
            </div>
            <div className="Grid-widget" style={{ fontSize: '.8rem', lineHeight: 1.5 }}>
              <h4>
                <span className="Badge Badge-primary">Certificado de Desgravamen DE-{header.issueNumber}</span>
              </h4>
              Fecha de Emisión: {header.date_issue != null ? moment(header.date_issue).format('DD/MM/YYYY h:mm a') : ''}
              <br/>
              Versión de Certificado v-{header.certificateVersion}
              <br/>
              <a
                className="Btn Btn-sm"
                href={certificate.replace(':code', 'de').replace(':rp_id', header.rpIdx).replace(':header_id', header.idx)}
                title="Ver Certificado"
                target="_blank"
              >
                Ver Certificado
              </a>
            </div>
            <div className="Grid-action">
              <Link to="/" className="Btn Btn-warning">
                <span className="oi" data-glyph="arrow-circle-left"></span> Volver al Panel
              </Link>
            </div>
            {
              log && 
              <div className="Grid-box2" style={{ marginTop: '1em' }}>
                <ReactJson src={log}
                  theme="ocean"
                  displayDataTypes={false}
                  iconStyle="circle"
                  displayObjectSize={false}
                  enableClipboard={false}
                />
              </div>
            }
            <div className="Grid-main">
              <div className="Card">
                <div className="Card-header Text-center">
                  <div className="onoffswitch">
                    <input
                      type="checkbox"
                      name="data-switch" id="data-switch"
                      checked={!editHeader}
                      className="onoffswitch-checkbox"
                      onChange={() => { this.setState({ editHeader: !editHeader }) }} />
                    <label className="onoffswitch-label" htmlFor="data-switch"></label>
                  </div>
                </div>
                <div className="Card-body">
                  <form onSubmit={(e) => this.handleUpdate(e, 'Header')}>
                    <fieldset>
                      <div className="Box">
                        <div className="Box-col3">
                          <label className="Label-dark Text-center">Tipo de cobertura</label>
                          <select name="coverage" value={header.coverage} onChange={this.handleChange} disabled={editHeader}>
                            {
                              _.map(data.coverages, (value, index) =>
                                <option value={index} key={index}>{value}</option>
                              )
                            }
                          </select>
                        </div>
                        <div className="Box-col3">
                          <label className="Label-dark Text-center">Monto solicitado</label>
                          <input type="text" name="amountRequested" value={header.amountRequested} onChange={this.handleChange} className="Form-option" disabled={editHeader} autoComplete="off" />
                          <select name="currency" value={header.currency} onChange={this.handleChange} disabled={editHeader}>
                            {
                              _.map(data.currencies, (value, index) =>
                                <option value={index} key={index}>{value}</option>
                              )
                            }
                          </select>
                        </div>
                        <div className="Box-col3">
                          <label className="Label-dark Text-center">Plazo del crédito</label>
                          <input type="text" name="term" value={header.term} onChange={this.handleChange} className="Form-option" disabled={editHeader} autoComplete="off" />
                          <select name="typeTerm" value={header.typeTerm} onChange={this.handleChange} disabled={editHeader}>
                            {
                              _.map(data.type_terms, (value, index) =>
                                <option value={index} key={index}>{value}</option>
                              )
                            }
                          </select>
                        </div>
                        <div className="Box-col3">
                          <label className="Label-dark Text-center">Producto</label>
                          <select name="creditProduct" value={header.creditProduct} onChange={this.handleChange} disabled={editHeader}>
                            {
                              _.map(data.credit_products, (value, index) =>
                                <option value={index} key={index}>{value}</option>
                              )
                            }
                          </select>
                        </div>
                      </div>
                      <div className="Box">
                        <div className="Box-col3">
                          <label className="Label-dark Text-center">Número de Póliza</label>
                          <select name="policyNumber" value={header.policyNumber} onChange={this.handleChange} disabled={editHeader}>
                            {
                              _.map(data.policies, (value, index) =>
                                <option value={index} key={index}>{value}</option>
                              )
                            }
                          </select>
                        </div>
                        <div className="Box-col3"></div>
                        <div className="Box-col3"></div>
                      </div>
                      <div className="Box" v-if="formData.edit_data">
                        <div className="Box-col Text-right">
                          <button className="Btn Btn-xs Btn-primary" type="submit" disabled={editHeader}>Guardar</button>
                        </div>
                      </div>
                    </fieldset>
                  </form>
                </div>
              </div>
              
              <div className="Card">
                <div className="Card-header Text-center">
                  <div className="onoffswitch">
                    <input
                      type="checkbox"
                      name="client-switch" id="client-switch"
                      checked={!editClient}
                      className="onoffswitch-checkbox"
                      onChange={() => { this.setState({ editClient: !editClient }) }} />
                    <label className="onoffswitch-label" htmlFor="client-switch"></label>
                  </div>
                </div>
                <div className="Card-body">
                  {
                    header.details.map((detail, index) => 
                      <form onSubmit={(e) => this.handleUpdate(e, 'Client')} className="Form-dataClient" key={index}>
                        <fieldset>
                          <div className="Box">
                            <div className="Box-col2">
                              <input type="hidden" name="idd" value={detail.idx} disabled={editClient} />
                              <label className="Label-dark Text-center">Nombres</label>
                              <input type="text" name="firstName" value={detail.client.firstName} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Ap. Paterno</label>
                              <input type="text" name="lastName" value={detail.client.lastName} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Ap. Materno</label>
                              <input type="text" name="motherLastName" value={detail.client.motherLastName} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Ap. de casada</label>
                              <input type="text" name="marriedName" value={detail.client.marriedName} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Doc. de identidad</label>
                              <input type="text" name="dni" value={detail.client.dni} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Complemento</label>
                              <input type="text" name="complement" value={detail.client.complement} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Extensión</label>
                              <select name="extension" value={detail.client.extension} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient}>
                                {
                                  _.map(data.extensions, (value, index) =>
                                    <option value={index} key={index}>{value}</option>
                                  )
                                }
                              </select>
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Fecha de nacimiento</label>
                              <input type="date" name="birthdate" value={moment(detail.client.birthdate).format('YYYY-MM-DD')} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Lugar de nacimiento</label>
                              <input type="text" name="birthPlace" value={detail.client.birthPlace} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Localidad</label>
                              <input type="text" name="locality" value={detail.client.locality} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col4">
                              <label className="Label-dark Text-center">Dirección</label>
                              <textarea type="text" name="homeAddress" value={detail.client.homeAddress} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient}></textarea>
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col4">
                              <label className="Label-dark Text-center">Dirección laboral</label>
                              <textarea type="text" name="businessAddress" value={detail.client.businessAddress} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient}></textarea>
                            </div>
                            <div className="Box-col3">
                              <label className="Label-dark Text-center">Lugar de Trabajo</label>
                              <input type="text" name="workplace" value={detail.client.workplace} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col5">
                              <label className="Label-dark Text-center">Ocupación (CAEDEC)</label>
                              <select name="activity" value={detail.client.activity} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient}>
                                {
                                  _.map(data.activities, (value, index) =>
                                    <option value={index} key={index}>{value}</option>
                                  )
                                }
                              </select>
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col4">
                              <label className="Label-dark Text-center">Descripción de actividad</label>
                              <textarea type="text" name="occupationDescription" value={detail.client.occupationDescription} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient}></textarea>
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Telefono 1</label>
                              <input type="text" name="phoneNumberHome" value={detail.client.phoneNumberHome} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Telefono 2</label>
                              <input type="text" name="phoneNumberMobile" value={detail.client.phoneNumberMobile} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Tel. Oficina</label>
                              <input type="text" name="phoneNumberOffice" value={detail.client.phoneNumberOffice} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Correo electónico</label>
                              <input type="text" name="email" value={detail.client.email} onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Peso</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">(Kg)</div>
                                <input type="text" name="weight" value={detail.client.weight} className="Input-addOnField" onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Estatura</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">(cm)</div>
                                <input type="text" name="height" value={detail.client.height} className="Input-addOnField" onChange={(e) => this.handleChange(e, index, 'c')} disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col">
                              <h4 className="Title-second">Beneficiarios</h4>
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Nombres</label>
                              <input type="text" name="beneficiaryFirstName" value={detail.beneficiaryFirstName} onChange={(e) => this.handleChange(e, index, 'd')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Ap. Paterno</label>
                              <input type="text" name="beneficiaryLastName" value={detail.beneficiaryLastName} onChange={(e) => this.handleChange(e, index, 'd')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Ap. Materno</label>
                              <input type="text" name="beneficiaryMotherLastName" value={detail.beneficiaryMotherLastName} onChange={(e) => this.handleChange(e, index, 'd')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Parentesco</label>
                              <input type="text" name="beneficiaryRelationship" value={detail.beneficiaryRelationship} onChange={(e) => this.handleChange(e, index, 'd')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Doc. de identidad</label>
                              <input type="text" name="beneficiaryDni" value={detail.beneficiaryDni} onChange={(e) => this.handleChange(e, index, 'd')} disabled={editClient} autoComplete="off" />
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center">Extensión</label>
                              <select name="beneficiaryExtension" value={detail.beneficiaryExtension} onChange={(e) => this.handleChange(e, index, 'd')} disabled={editClient}>
                                {
                                  _.map(data.extensions, (value, index) =>
                                    <option value={index} key={index}>{value}</option>
                                  )
                                }
                              </select>
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col">
                              <h4 className="Title-second">Montos y Saldos</h4>
                            </div>
                          </div>
                          <div className="Box">
                            <div className="Box-col3">
                              <label className="Label-dark Text-center" style={styles.labelMd}>Monto Actual Solicitado</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">({`${header.currency[0].toUpperCase()}${header.currency.slice(1).toLowerCase()}`})</div>
                                <input type="text" name="amount" value={detail.amount} onChange={(e) => this.handleChange(e, index, 'd')} className="Input-addOnField" disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center" style={styles.labelMd}>Saldo deudor actual</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">(Bs)</div>
                                <input type="text" name="balance" value={detail.balance} onChange={(e) => this.handleChange(e, index, 'd')} className="Input-addOnField" disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center" style={styles.labelMd}>Monto Créditos Paralelos</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">(Bs)</div>
                                <input type="text" name="parallelCredit" value={detail.parallelCredit} onChange={(e) => this.handleChange(e, index, 'd')} className="Input-addOnField" disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                            <div className="Box-col3">
                              <label className="Label-dark Text-center" style={styles.labelMd}>Monto Actual Acumulado</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">(Bs)</div>
                                <input type="text" name="cumulus" value={detail.cumulus} onChange={(e) => this.handleChange(e, index, 'd')} className="Input-addOnField" disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                            <div className="Box-col2">
                              <label className="Label-dark Text-center" style={styles.labelMd}>Monto de Operación a cancelar</label>
                              <div className="Input-addOn">
                                <div className="Input-addOnItem">(Bs)</div>
                                <input type="text" name="refinancing" value={detail.refinancing} onChange={(e) => this.handleChange(e, index, 'd')} className="Input-addOnField" disabled={editClient} autoComplete="off" />
                              </div>
                            </div>
                          </div>
                          {
                            !editClient &&
                            <div className="Box">
                              <div className="Box-col Text-right">
                                <button className="Btn Btn-xs Btn-primary" type="submit" disabled={editClient}>Guardar</button>
                              </div>
                            </div>
                          }
                        </fieldset>
                        <hr style={{ margin: '1em 0' }}/>
                      </form>
                    )
                  }
                </div>
              </div>
            </div>

            <div className="Grid-footer" style={{ height: '200px' }}>
            </div>
          </div>
        }

        <NotificationContainer />
      </div>
    )
  }
}

const styles = {
  labelMd: {
    fontSize: '.75rem',
  },
}