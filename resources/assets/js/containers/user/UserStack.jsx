import React, { Component } from 'react'
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';

import reducer from './../../reducers/user';
import UserContainer from './UserContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk),
);

export default class UserStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <UserContainer />
      </Provider>
    )
  }
}