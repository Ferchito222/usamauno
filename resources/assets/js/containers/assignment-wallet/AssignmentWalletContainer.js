import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { NotificationContainer } from 'react-notifications';
import { eLoading } from 'helpers'

import * as actions from './../../actions/assignment-wallet';
import { AssignmentWalletView } from './../../components/assignment-wallet/AssignmentWalletView';

class AssignmentWalletContainer extends Component {
  constructor(props) {
    super(props);

    this.getUsers = this.getUsers.bind(this);
  }

  componentDidMount = () => {
    this.getUsers();
  }

  getUsers = () => {
    eLoading(true);

    const $url = document.getElementById('roa-wallet-users').value;

    axios.get($url)
      .then(response => {
        eLoading(false);

        const { data } = response;

        if (response.status === 200) {
          this.props.actions.setUsers(data.api.users, data.api.headers);
        }
      })
      .catch(e => {
        // console.info(e);
      });
  }

  render() {
    const { usersFrom, usersTo, headers, records } = this.props;

    return (
      <div>
        <AssignmentWalletView
          usersFrom={usersFrom}
          usersTo={usersTo}
          headers={headers}
          records={records}
          handleCertificates={this.props.actions.getCertificates}
          handleAssignment={this.props.actions.assignment}
        />

        <NotificationContainer />
      </div>
    )
  }
}

const mapStateToProps = (state, props) => {
  return {
    usersFrom: state.get('assignmentWallet').get('usersFrom'),
    usersTo: state.get('assignmentWallet').get('usersTo'),
    headers: state.get('assignmentWallet').get('headers'),
    records: state.get('assignmentWallet').get('records'),
  }
}

const mapDispatchToProps = (dispatch) => {
  return {
    actions: bindActionCreators(actions, dispatch),
  }
}

export default connect(mapStateToProps, mapDispatchToProps)(AssignmentWalletContainer);