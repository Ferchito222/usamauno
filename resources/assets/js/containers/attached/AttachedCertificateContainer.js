import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import { eLoading } from 'helpers';

import * as actions from './../../actions/attached-certificate';
import { AttachedCertificateSearchView } from './../../components/attached/AttachedCertificateSearchView';
import { AttachedCertificateListView } from './../../components/attached/AttachedCertificateListView';

class AttachedCertificateContainer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      formData: this.initialState,
    };
  }

  get initialState() {
    return {
      number: '',
      user: '',
      client: '',
      dateBegin: '',
      dateEnd: '',
    }
  }

  componentDidMount = () => {
    this.getData();
  }
  
  handleInputChange = (event) => {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    const { formData } = this.state;
    formData[name] = value;

    this.setState({ formData }, () => {
      this.props.actions.filterData(formData);
    });

    // this.setState({ formData }, () => this.props.handleFilterData(formData));
  }

  getData = () => {
    eLoading(true);

    const $url = document.getElementById('roAc-lists').value;
    
    axios.get($url, {
      params: {}
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const { api } = response.data;

          this.props.actions.setData(api);
        }
      })
      .catch(e => {
        // console.info(e);
      });
  }

  attachedDelete = (e, id) => {
    e.preventDefault();

    const confirm = window.confirm(`Esta seguro de eliminar el Cetificado Adjunto?`);

    if (confirm) {
      eLoading(true);
      const $url = document.getElementById('roAc-delete').value.replace(':id', id);

      axios.delete($url, {})
        .then(response => {
          eLoading(false);

          if (response.status === 200) {
            if (response.data.delete) {
              const { formData } = this.state;
              this.props.actions.fileDelete(id, formData);

              NotificationManager.success('El Certificado Adjunto fue eliminado.');
            }
          }
        })
        .catch(e => {
          eLoading(false);

          NotificationManager.warning('No se pudo eliminar el Certificado Adjunto.');

          if (e.response) {
          } else if (e.request) {
          } else {
            // console.log('Error', e.message);
          }
        });
    }
  }

  handleResetForm = () => {
    this.setState({ formData: this.initialState }, () => {
      this.props.actions.filterData(this.state.formData);
    });
  }

  render() {
    const { formData } = this.state;
    const { lists, details } = this.props;

    return (
      <div className="Grid-container">
        <div className="Grid-header">
          <h5>Eliminación de Certificados Adjuntos</h5>
        </div>
        <div className="Grid-box2">
          <AttachedCertificateSearchView
            formData={formData}
            handleInputChange={this.handleInputChange}
            handleResetForm={this.handleResetForm}
            handleGetData={this.getData}
          />
        </div>
        <div className="Grid-main">
          <AttachedCertificateListView
            lists={lists}
            details={details}
            handleAttachedDelete={this.attachedDelete}
          />
        </div>

        <NotificationContainer />
      </div>
    )
  }
}

const mapStateToProps = (state, props) => {
  return {
    lists: state.get('attachedCertificate').get('lists'),
    details: state.get('attachedCertificate').get('details'),
  }
};

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch)
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(AttachedCertificateContainer) ;