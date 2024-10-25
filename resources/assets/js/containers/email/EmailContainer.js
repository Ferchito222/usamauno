import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import { eLoading, closeModal } from 'helpers';

import * as actions from './../../actions/email';
import { EmailHeaderView } from './../../components/email/EmailHeaderView';
import { EmailListView } from './../../components/email/EmailListView';
import { EmailFormView } from './../../components/email/EmailFormView';
import ModalView from './../../components/ModalView';

const components = {
  EmailFormView,
}

class EmailContainer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      view: null,
    };

    this.getEmails = this.getEmails.bind(this);
    this.setView = this.setView.bind(this);
    this.handleEmailSetUp = this.handleEmailSetUp.bind(this);
    this.handleEmailDelete = this.handleEmailDelete.bind(this);
    this.handleEmailTurnOff = this.handleEmailTurnOff.bind(this);
  }

  componentDidMount = () => {
    this.getEmails();
  }

  getEmails() {
    eLoading(true);

    const $url = document.getElementById('re-list').value;

    axios.get($url)
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const { api } = response.data;
          this.props.actions.list(api);
        }
      })
      .catch(e => {
        // console.info(e);
      });
  }

  setView = (view = null, emailId = null) => {
    this.props.actions.setEmail(emailId);

    this.setState({
      view,
    });
  }

  handleEmailSetUp = (email, edit = false) => {
    if (! edit) {
      this.props.actions.emailCreate(email);
    } else {
      this.props.actions.emailEdit(email);
    }

    this.setView();
    closeModal();
  }

  handleEmailTurnOff = (e, emailId, pivotId, turnOff) => {
    e.preventDefault();
    eLoading(true);

    const $url = document.getElementById('re-turnoff').value;

    axios.put($url, {
      id: pivotId,
      turn_off: turnOff,
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          turnOff = response.data.turnOff;
          const message = `Correo Electrónico ${(turnOff ? 'habilitado' : 'deshabilitado')}.`;

          this.props.actions.emailTurnOff(emailId, pivotId, turnOff);

          if (turnOff) {
            NotificationManager.success(message, 'Mensaje importante');
          } else {
            NotificationManager.warning(message, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        if (e.response) {
        } else if (e.request) {
        } else {
          // console.log('Error', e.message);
        }

        // console.log(e.config);
      });
  }

  handleEmailDelete = (e, emailId) => {
    eLoading(true);
    e.preventDefault();

    const $url = document.getElementById('re-delete').value;

    axios.delete($url, { params: { id: emailId } })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          this.props.actions.emailDelete(emailId);

          NotificationManager.success('El Correo Electrónico fué eliminado correctamente.', 'Mensaje importante');
        }
      })
      .catch(e => {
        eLoading(false);

        if (e.response) {
          NotificationManager.warning('El Correo Electrónico no pudo ser eliminado.', 'Mensaje importante');
        } else if (e.request) {
          // console.log(e.request);
        } else {
          // console.log('Error', e.message);
        }
        // console.log(e.config);
      });
  }

  render() {
    const { emails, products, email } = this.props;
    const { view } = this.state;
    const ComponentView = view ? components[view] : null;

    return (
      <div>
        <div className="Grid-container">
          <EmailHeaderView
            setView={this.setView}
          />
          
          <div className="Grid-main">
            <EmailListView
              emails={emails}
              products={products}
              setView={this.setView}
              handleEmailDelete={this.handleEmailDelete}
              handleEmailTurnOff={this.handleEmailTurnOff}
            />
          </div>
        </div>
        {
          ComponentView &&
          <ModalView
            handleClose={this.setView}
          >
            <ComponentView
              email={email}
              products={products}
              handleEmailSetUp={this.handleEmailSetUp}
            />
          </ModalView>
        }
        <NotificationContainer />
      </div>
    )
  }
}


const mapStateToProps = (state, props) => {
  return {
    emails: state.get('email').get('emails'),
    products: state.get('email').get('products'),
    email: state.get('email').get('email'),
  }
}

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(EmailContainer);