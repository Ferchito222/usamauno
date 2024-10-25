import React, { Component, Fragment } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import _ from 'underscore';
import { eLoading } from 'helpers';

import * as actions from './../../actions/credit-line';
import { CreditLineFormView } from './../../components/de/credit-line/CreditLineFormView';
import { CreditLineListView } from './../../components/de/credit-line/CreditLineListView';

class CreditLineContainer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      formData: this.initialState,
      minItems: 0,
      maxItems: 0,
      confirmed: false,
      edit: false,
      errors: {},
      coverages: JSON.parse(document.getElementById('cl-coverages').value),
      policyTypes: JSON.parse(document.getElementById('cl-policy-types').value),
    };
  }

  get initialState() {
    const coverageVal = _.keys(JSON.parse(document.getElementById('cl-coverages').value))[0];
    const policyTypeVal = _.keys(JSON.parse(document.getElementById('cl-policy-types').value))[0];

    return {
      numberLine: '',
      coverage: coverageVal,
      policyType: policyTypeVal,
      clients: [],
    };
  }

  handleInputChange = (event, index = null) => {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;
    let formData = this.state.formData;

    if (index !== null) {
      formData.clients[index][name] = value;

      if (name === 'insured') {
        formData.clients[index]['disabled'] = !value;
        formData.clients[index]['percentageCredit'] = (formData.coverage === 'MC' && formData.policyType === 'TE' && value) ? 100 : 0;
        formData.clients[index]['percentage'] = 0;
      }

      if (name === 'percentageCredit' && value > 100) {
        formData.clients[index]['percentageCredit'] = 100;
      }
    } else {
      formData[name] = value;
    }

    if (name === 'coverage') {
      let minItems = 0;
      let maxItems = 0;

      switch (value) {
        case 'IC':
          minItems = 1;
          maxItems = 1;
          formData.clients = [];
          break;
        case 'MC':
          minItems = 2;
          maxItems = 10;

          if (formData.policyType === 'TE') {
            const clients = formData.clients.map((c, i) => { c.percentageCredit = 100; c.percentageCreditDisabled = true; return c; });
            formData.clients = clients;
          }
          break;
        default:
          formData.clients = [];
          break;
      }

      this.setState({
        minItems,
        maxItems,
      });
    }

    if (name === 'policyType') {
      const clients = formData.clients.map((c, i) => {
        c.percentageCreditDisabled = (value !== 'TE' ? false : true);
        c.percentageCredit = (value === 'TE' && formData.coverage === 'MC' && c.insured ? 100 : c.percentageCredit);

        return c;
      });

      formData.clients = clients;
    }

    this.setState({ formData });
  }

  handleAddRow = (formData) => {
    this.setState({ formData });
  }

  handleDeleteRow = (clients) => {
    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        clients,
      }
    }));
  }

  handleNumberClient = (formData) => {
    this.setState({ formData: formData });
  }

  store = (event, sum) => {
    event.preventDefault();
    eLoading(true);

    const { formData, minItems, edit } = this.state;

    if ((formData.clients.length < minItems) || (formData.clients.length > 0 && !sum)) {
      eLoading(false);

      return false;
    }

    const $url = document.getElementById('rde-cl-store').value;

    this.props.actions.creditLineRemove();

    formData.edit = edit;

    axios.post($url, formData)
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const data = response.data;

          if (data.success) {
            this.props.actions.creditLineStore(data.creditLine);

            this.setState({
              formData: this.initialState,
              edit: false,
            });

            NotificationManager.success(data.message, 'Mensaje importante');
          }
        }

        this.setState({ errors: {} });
      })
      .catch(e => {
        eLoading(false);

        if (e.response) {
          this.setState({ errors: e.response.data });

          if (e.response.data.warning) {
            NotificationManager.warning(e.response.data.message, 'Mensaje importante');
          }
        } else if (e.request) {
        } else {
          // console.log('Error', e.message);
        }
      });
  }

  edit = (e) => {
    e.preventDefault();

    const { formData } = this.state;
    const creditLine = this.props.creditLine.toJS();

    this.setState({ formData: creditLine, edit: true });
    this.props.actions.creditLineRemove();
  }

  confirm = (e) => {
    e.preventDefault();
    eLoading(true);

    const $url = document.getElementById('rde-cl-confirm').value;
    const { creditLine } = this.props;

    axios.put($url, {
      id: creditLine.get('idx')
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const data = response.data;

          if (data.success) {
            this.props.actions.creditLineConfirm(data.confirm);
            this.setState({ confirmed: true });

            NotificationManager.success(data.message, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        eLoading(false);

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
    const { formData, errors, minItems, maxItems, confirmed, coverages, policyTypes } = this.state;
    const { creditLine } = this.props;

    return (
      <Fragment>
        <div className="page-header">
          <div className="page-header-content">
            <div className="page-title" style={{ paddingTop: '0' }}>
              <h4>
                <i className="icon-arrow-left52 position-left"></i>
                <span className="text-semibold">Registrar Líneas de Crédito</span>
              </h4>
            </div>
          </div>
        </div>

        <div className="row">
          <div className="col-md-12">
            <div className="panel panel-flat border-top-primary">
              <div className="panel-body">
                {
                  creditLine.size > 0 &&
                  <Fragment>
                    <CreditLineListView
                      creditLine={creditLine}
                      confirmed={confirmed}
                      coverages={coverages}
                      policyTypes={policyTypes}
                    />

                    {!confirmed &&
                      <div className="row" style={{ marginTop: '10px' }}>
                        <div className="col-md-8 col-md-offset-2 text-center">
                          <button type="button" className="btn btn-primary btn-sm" onClick={this.confirm}>Confirmar</button>
                          <button type="button" className="btn btn-default btn-sm" style={{ marginLeft: '5px' }} onClick={this.edit}>Editar</button>
                        </div>
                      </div>
                    }
                    <hr />
                  </Fragment>
                }
                
                <CreditLineFormView
                  formData={formData}
                  errors={errors}
                  minItems={minItems}
                  maxItems={maxItems}
                  creditLine={creditLine}
                  coverages={coverages}
                  policyTypes={policyTypes}
                  handleInputChange={this.handleInputChange}
                  handleAddRow={this.handleAddRow}
                  handleDeleteRow={this.handleDeleteRow}
                  handleNumberClient={this.handleNumberClient}
                  handleCreditLineStore={this.store}
                />
              </div>
            </div>
          </div>
        </div>

        <NotificationContainer />
      </Fragment>
    )
  }
}

const mapStateToProps = (state, props) => ({
  creditLine: state.get('creditLine').get('creditLine'),
});

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch)
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(CreditLineContainer);