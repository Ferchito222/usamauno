import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import _ from 'underscore'
import { eLoading, closeModal } from 'helpers';

import * as actions from './../../actions/user';
import { UserHeaderView } from './../../components/user/UserHeaderView';
import { UserListView } from './../../components/user/UserListView';
import { UserFormView } from './../../components/user/UserFormView';
import PasswordView from './../../components/user/PasswordView';
import ModalView from './../../components/ModalView';

import 'react-notifications/dist/react-notifications.css';

const components = {
  UserFormView,
  PasswordView,
};

class UserList extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {},
      query: '',
      view: null,
      minChar: 0,
      maxChar: 0,
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.getUsers = this.getUsers.bind(this);
    this.setView = this.setView.bind(this);
    this.handleUserSetUp = this.handleUserSetUp.bind(this);
    this.handlePasswordEdit = this.handlePasswordEdit.bind(this);
    this.handleUserActive = this.handleUserActive.bind(this);
    this.handlePasswordTurnOff = this.handlePasswordTurnOff.bind(this);
    this.toggle = this.toggle.bind(this);
  }

  componentDidMount() {
    this.getUsers();
  }

  componentDidUpdate(prevProps, prevState) {
    if (this.state.query !== prevState.query) {
      this.props.actions.filter(this.state.query);
    }
  }

  handleInputChange(event) {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  getUsers() {
    const $url = document.getElementById('ru-list').value;
    const $exportUrl = document.getElementById('ru-export').value;

    eLoading(true);

    axios.get($url)
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const data = response.data;

          this.setState({
            data: data.data,
            minChar: data.settings.min_char,
            maxChar: data.settings.max_char,
          });

          this.props.actions.userList(data.api, data.user_type, $exportUrl);
        }
      })
      .catch(e => {
        // console.info(e);
      });
  }

  setView = (view = null, userId = null, reset = false) => {
    this.props.actions.setUser(userId);
    this.props.actions.setReset(reset);

    this.setState({
      view,
    });
  }

  handleUserSetUp = (user, edit = false) => {
    const { query } = this.state;

    if (edit) {
      this.props.actions.userEdit(user, query);
    } else {
      this.props.actions.userCreate(user, query);
    }

    closeModal();
    this.setView(null);
  }

  handlePasswordEdit = (id) => {
    const { query } = this.state;

    this.props.actions.passwordEdit(id, query);

    closeModal();
    this.setView(null);
  }

  handleUserActive = (e, user) => {
    e.preventDefault();

    const userId = user.get('idx');
    const userFullName = user.get('fullName');
    let active = user.get('active');
    const { query } = this.state;
    
    const $url = document.getElementById('ru-activate').value;
    const confirm = window.confirm(`Esta seguro en ${!active ? 'habilitar' : 'deshabilitar'} al usuario ${userFullName}?`);

    if (confirm) {
      eLoading(true);

      axios.put($url, {
        id: userId,
        active: active,
      })
        .then(response => {
          eLoading(false);

          if (response.status === 200) {
            active = response.data.active;
            
            this.props.actions.userActive(userId, active, query);
            NotificationManager.success(`Usuario ${active ? 'habilitado' : 'deshabilitado'}`, 'Mensaje importante');
          }
        })
        .catch(e => {
          eLoading(false);

          if (e.response) {
          } else if (e.request) {
          } else {
            console.log('Error', e.message);
          }
        });
    }
  }

  handlePasswordTurnOff = (e, user) => {
    e.preventDefault();
    eLoading(true);

    const $url = document.getElementById('ru-password-turnoff').value;
    const userId = user.get('idx');
    let turnOff = user.get('passwordChange');
    const { query } = this.state;

    axios.put($url, {
      id: userId,
      turn_off: ! turnOff,
    })
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          turnOff = response.data.turn_off;

          const mess = 'Cambio de contraseña ' + (turnOff ? 'activada' : 'desactivada') + '.';

          this.props.actions.passwordTurnOff(userId, turnOff, query);

          if (turnOff) {
            NotificationManager.success(mess, 'Mensaje importante');
          } else {
            NotificationManager.warning(mess, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        if (e.response) {
        } else if (e.request) {
        } else {
          console.log('Error', e.message);
        }

        // console.log(e.config);
      });
  }

  toggle = (e) => {
    e.target.classList.toggle('active')
  }

  render() {
    const { lists, user, userType, reset, exportUrl } = this.props;
    const { data, query, view, minChar, maxChar } = this.state;
    const ComponentView = view ? components[view] : null;

    return (
      <div>
        <div className="Grid-container">
          <UserHeaderView
            query={query}
            userType={userType}
            exportUrl={exportUrl}
            handleInputChange={this.handleInputChange}
            handleSetView={this.setView}
          />
          
          <div className="Grid-main">
            <UserListView
              lists={lists}
              userType={userType}
              products={data.products}
              companies={data.companies}
              setView={this.setView}
              handleUserActive={this.handleUserActive}
              handlePasswordTurnOff={this.handlePasswordTurnOff}
              toggle={this.toggle}
            />
          </div>

          <div className="Grid-footer" style={{ height: '200px' }}></div>
          
          {
            ComponentView && 
            <ModalView
              handleClose={this.setView}
            >
              <ComponentView
                data={data}
                userType={userType}
                user={user}
                reset={reset}
                minLength={minChar}
                maxLength={maxChar}
                handleUserSetUp={this.handleUserSetUp}
                handlePasswordEdit={this.handlePasswordEdit}
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
    users: state.get('user').get('users'),
    lists: state.get('user').get('lists'),
    user: state.get('user').get('user'),
    userType: state.get('user').get('userType'),
    reset: state.get('user').get('reset'),
    exportUrl: state.get('exportUrl').get('fullPath'),
  }
}

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(UserList);