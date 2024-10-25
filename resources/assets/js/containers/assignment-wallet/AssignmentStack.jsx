import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';

import reducer from './../../reducers/assignment-wallet';
import AssignmentWalletContainer from './AssignmentWalletContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk)
);

export default class AssignmentStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <AssignmentWalletContainer />
      </Provider>
    )
  }
}
