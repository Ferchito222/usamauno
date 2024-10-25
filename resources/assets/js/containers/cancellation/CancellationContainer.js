import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import _ from 'underscore';
import { eLoading, closeModal } from 'helpers';

import * as actions from './../../actions/cancellation';
import { CancellationListView } from './../../components/de/cancellation/CancellationListView';
import { CancellationSearchView } from './../../components/de/cancellation/CancellationSearchView';
import { CancellationFormView } from './../../components/de/cancellation/CancellationFormView';
import PaginationView from './../../commons/PaginationView';
import ModalView from './../../components/ModalView';

import 'react-notifications/lib/notifications.css';

const components = {
  CancellationFormView,
};

class CancellationContainer extends Component {
  constructor(props) {
    super(props);
    
    this.state = {
      formData: {},
      data: {},
      certificate: document.getElementById('r-c').value,
      view: null,
      pagination: {},
      url: document.getElementById('roc-data').value,
    };
  }

  get initialState() {
    const { products } = this.state.data;
    const productVal = Object.keys(products)[0];

    return {
      product: productVal,
      policy_number: '',
      city: '',
      agency: '',
      username: '',
      client: '',
      dni: '',
      date_begin: '',
      date_end: '',
    };
  }

  componentDidMount = () => {
    this.getHeaders();
  }

  componentDidUpdate = (prevProps, prevState) => {
    const { formData, data } = this.state;

    if (prevState.data !== data) {
      this.setState({ formData: this.initialState });
    }
  }

  handleInputChange = (event) => {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        [name]: value,
      }
    }));
  }

  getHeaders = (page = null, search = false) => {
    eLoading(true);

    let params = {
      ...this.state.formData,
    };

    if (Number.isInteger(page)) {
      params.page = page;
    }

    axios.get(this.state.url, {
      params: params,
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const { api, data } = response.data;
          
          this.props.actions.list(api);
          this.makePagination(api);

          this.setState({ data });

          if (Object.keys(api.request).length > 1) {
            delete api.request['page'];

            this.setState({
              formData: api.request,
            });
          }

          if (Number.isInteger(page)) {
            document.getElementById('data-Table').scrollIntoView(true);
          }
        }
      })
      .catch(e => {
        eLoading(false);
      });
  }

  makePagination = (api) => {
    const pagination = {
      currentPage: api.current_page,
      lastPage: api.last_page,
      nextPageUrl: api.next_page_url,
      prevPageUrl: api.prev_page_url,
      from: api.from,
      perPage: api.per_page,
      to: api.to,
      total: api.total,
    };

    this.setState({ pagination });
  }

  setView = (view = null) => {
    this.setState({
      view,
    });
  }

  handleSetHeader = (view, idx) => {
    this.props.actions.setHeader(idx);

    this.setView(view);
  }

  reset = () => {
    this.setState({ formData: this.initialState });
  }

  render() {
    const { headers, details, header } = this.props;
    const { formData, data, certificate, view, pagination, url } = this.state;
    const ComponentView = view ? components[view] : null;

    return (
      <div>
        <div className="Grid-container">
          <div className="Grid-header">
            <h5>Anulación de Certificados</h5>
          </div>

          <div className="Grid-box2">
            {
              !_.isEmpty(formData) && (
                <CancellationSearchView
                  formData={formData}
                  data={data}
                  handleInputChange={this.handleInputChange}
                  handleReset={this.reset}
                  handleSearch={this.getHeaders}
                />
              )
            }
          </div>
          
          <div className="Grid-main">
            {
              !_.isEmpty(formData) && (
                <CancellationListView
                  headers={headers}
                  details={details}
                  data={data}
                  product={formData.product}
                  certificate={certificate}
                  handleSetHeader={this.handleSetHeader}
                />
              )
            }
          </div>

          <div className="Grid-footer" style={{ height: '200px' }}>
            <PaginationView
              pagination={pagination}
              handlePage={this.getHeaders}
            />
          </div>
          
          {
            ComponentView && 
            <ModalView
              handleClose={this.setView}
            >
              <CancellationFormView
                header={header}
                product={formData.product}
                handleCancel={this.props.actions.cancel}
              />
            </ModalView>
          }
        </div>

        <NotificationContainer />
      </div>
    )
  }
}

const mapStateToProps = (state, props) => {
  return {
    headers: state.get('cancellation').get('headers'),
    details: state.get('cancellation').get('details'),
    header: state.get('cancellation').get('header'),
  }
}

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(CancellationContainer);