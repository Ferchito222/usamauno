import React, { Component } from 'react';
import _ from 'underscore';
import { eLoading } from 'helpers';

export class CreditLineFormView extends Component {
  constructor(props) {
    super(props);
    this.state = {
      sum: false,
    }; 
  }

  checkPercentageCredit = () => {
    const { formData } = this.props;
    const clients = formData.clients.filter(c => c.insured);
    const sum = _.pluck(clients, 'percentageCredit').reduce((a, b) => a + parseInt(b), 0);

    this.setState({ sum: (sum >= 100 ? true : false) });
  }

  addRow = (e) => {
    e.preventDefault();

    const { formData, maxItems } = this.props;

    const client = {
      dni: '',
      complement: '',
      number: '',
      numbers: [],
      percentageCredit: (formData.policyType === 'TE' && formData.coverage === 'MC') ? 100 : 0,
      percentage: 0,
      insured: true,
      disabled: false,
      percentageCreditDisabled: (formData.policyType === 'TE' && formData.coverage === 'MC') ? true : false,
    };

    if (formData.clients.length < maxItems) {
      formData.clients.push(client);

      this.props.handleAddRow(formData);

      this.checkPercentageCredit();
    }
  }

  deleteRow = (e, index) => {
    const { formData } = this.props;
    const clients = formData.clients.filter((client, i) => index != i);

    this.props.handleDeleteRow(clients);

    this.checkPercentageCredit();
  }

  getNumberClient = (e, index) => {
    const { formData} = this.props;
    const dni = formData.clients[index].dni.trim();
    const complement = formData.clients[index].complement.trim();

    if (! /^\s*$/.test(dni)) {
      eLoading(true);

      const $url = document.getElementById('rde-cl-number-client').value;

      axios.get($url, {
        params: {
          dni: dni,
          complement: complement
        }
      })
        .then(response => {
          eLoading(false);

          if (response.status === 200) {
            formData.clients[index].numbers = response.data.numbers;

            if (response.data.numbers.length > 0) {
              formData.clients[index].number = _.first(response.data.numbers);
            }

            this.props.handleNumberClient(formData);
          }
        })
        .catch(e => {
          eLoading(false);
        });
    }
  }

  render() {
    const { sum } = this.state;
    const {
      formData,
      errors,
      minItems,
      maxItems,
      creditLine,
      confirmed,
      edit,
      coverages,
      policyTypes
    } = this.props;

    return (
      <form onSubmit={(e) => this.props.handleCreditLineStore(e, sum)}>
        <div className="row" style={{ fontSize: '90%' }}>
          <div className="col-md-8 col-md-offset-2">
            <div className="row">
              <div className="col-xs-4">
                <div className="form-group">
                  <label className="control-label label_required">Número de Línea Madre: </label>
                  <input className="form-control" style={styles.inputStyle} type="text" name="numberLine"
                    value={formData.numberLine} onChange={this.props.handleInputChange}
                    placeholder="Número de Operación" autoComplete="off" />
                  {errors.numberLine && <label className="validation-error-label">{errors.numberLine[0]}</label>}
                </div>
              </div>
              <div className="col-xs-4">
                <div className="form-group">
                  <label className="control-label label_required">Cobertura: </label>
                  <select className="form-control form-control-sm" style={styles.inputStyle} name="coverage"
                    value={formData.coverage} onChange={this.props.handleInputChange}>
                    {
                      _.map(coverages, (value, key) => (
                        <option value={key} key={key}>{value}</option>
                      ))
                    }
                  </select>
                  {errors.coverage && <label className="validation-error-label">{errors.coverage[0]}</label>}
                </div>
              </div>
              <div className="col-xs-4">
                <div className="form-group">
                  <label className="control-label label_required">Tipo de Póliza: </label>
                  <select className="form-control form-control-sm" style={styles.inputStyle} name="policyType"
                    value={formData.policyType} onChange={this.props.handleInputChange}>
                    {
                      _.map(policyTypes, (value, key) => (
                        <option value={key} key={key}>{value.name}</option>
                      ))
                    }
                  </select>
                  {errors.policyType && <label className="validation-error-label">{errors.policyType[0]}</label>}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="row" style={{ fontSize: '90%' }}>
          <div className="col-md-10 col-md-offset-1">
            {formData.coverage != '' && formData.clients.length < minItems && 
              <div className="alert alert-danger" style={{ marginBottom: '0' }}>
                Debe registrar como mínimo {minItems} Cliente(s).
              </div>
            }

            <table className="table table-striped">
              <thead>
                <tr>
                  <th className="col-xs-1"></th>
                  <th className="col-xs-2">Documento de Identidad</th>
                  <th className="col-xs-2">Complemento</th>
                  <th className="col-xs-1"></th>
                  <th className="col-xs-2">Nº de Cliente</th>
                  <th className="col-xs-1">% Participación</th>
                  <th className="col-xs-2">% Extra Prima</th>
                  <th className="col-xs-1">Asegurado</th>
                </tr>
              </thead>
              <tbody>
                {
                  formData.clients.map((client, index) => {
                    return (
                      <tr key={index}>
                        <td className="text-right" style={styles.tdStyle} style={{ textAlign: 'right' }}>
                          <span className="glyphicon glyphicon-minus-sign" aria-hidden="true" 
                            style={{ cursor: 'pointer' }}
                            onClick={(e) => { this.deleteRow(e, index) }}>
                          </span>
                        </td>
                        <td style={styles.tdStyle}>
                          <input className="form-control has-error" style={styles.inputStyle} type="text"
                            name="dni" value={formData.clients[index].dni}
                            onChange={(e) => { this.props.handleInputChange(e, index) }} 
                            autoComplete="off" />
                          {errors['clients.' + index + '.dni'] && <span className="text-danger">Obligatorio</span>}
                        </td>
                        <td style={styles.tdStyle}>
                          <input className="form-control" style={styles.inputStyle} type="text"
                            name="complement" value={formData.clients[index].complement}
                            onChange={(e) => { this.props.handleInputChange(e, index) }} 
                            autoComplete="off" />
                          {errors['clients.' + index + '.complement'] && <span className="text-danger">Obligatorio</span>}
                        </td>
                        <td style={styles.tdStyle}>
                          <button type="button" className="btn btn-default btn-xs" 
                            aria-label="Left Align" onClick={(e) => this.getNumberClient(e, index)}>
                            <span className="glyphicon glyphicon-search" aria-hidden="true"></span>
                          </button>
                        </td>
                        <td style={styles.tdStyle}>
                          <select className="form-control form-control-sm" style={styles.inputStyle}
                            name="number" value={formData.clients[index].number}
                            onChange={(e) => { this.props.handleInputChange(e, index) }}>
                            {
                              _.map(formData.clients[index].numbers, (value, index_nl) => (
                                <option value={value} key={index_nl}>{value}</option>
                              ))
                            }
                          </select>
                          {errors['clients.' + index + '.number'] && <span className="text-danger">Obligatorio</span>}
                        </td>
                        <td style={styles.tdStyle}>
                          <input className="form-control" style={styles.inputStyle} type="text"
                            name="percentageCredit" value={formData.clients[index].percentageCredit}
                            disabled={formData.clients[index].disabled || formData.clients[index].percentageCreditDisabled}
                            onChange={(e) => { this.props.handleInputChange(e, index); this.checkPercentageCredit(); }} 
                            autoComplete="off" />
                          {errors['clients.' + index + '.percentageCredit'] && <span className="text-danger">Obligatorio</span>}
                        </td>
                        <td style={styles.tdStyle}>
                          <input className="form-control" style={styles.inputStyle} type="text"
                            name="percentage" value={formData.clients[index].percentage}
                            disabled={formData.clients[index].disabled}
                            onChange={(e) => { this.props.handleInputChange(e, index) }} 
                            autoComplete="off" />
                          {errors['clients.' + index + '.percentage'] && <span className="text-danger">Obligatorio</span>}
                        </td>
                        <td style={styles.tdStyle}>
                          <div className="onoffswitch">
                            <input className="onoffswitch-checkbox" type="checkbox" 
                              name="insured" id={'isChecked' + index} 
                              checked={formData.clients[index].insured} 
                              onChange={(e) => { this.props.handleInputChange(e, index); this.checkPercentageCredit(); }} />
                            <label className="onoffswitch-label" htmlFor={'isChecked' + index}></label>
                          </div>
                        </td>
                      </tr>
                    )
                  })
                }
              </tbody>
              <tfoot>
                <tr>
                  <td colSpan="4"></td>
                  <td colSpan="2">
                    {formData.clients.length > 0 && 
                      <div className={"alert " + (sum ? 'alert-info' : 'alert-warning')} 
                        style={{ padding: '5px' }} role="alert">
                        La suma de los porcentajes de participación debe ser mayor o igual al 100%.
                      </div>
                    }
                  </td>
                  <td></td>
                  <td>
                    <button type="button" className="btn btn-info" aria-label="Left Align"
                      onClick={this.addRow}>
                      <span className="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                    </button>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        {
          (creditLine.size === 0 || confirmed) && 
          <div className="col-xs-12 text-center" style={{ marginTop: '15px' }}>
            <button type="submit" className="btn btn-success btn-sm">
              {edit ? 'Actualizar' : 'Guardar'}
            </button>
          </div>
        }
      </form>
    )
  }
}

const styles = {
  inputStyle: {
    height: '30px',
    backgroundColor: 'white',
  },
  tdStyle: {
    padding: '6px 5px'
  }
}