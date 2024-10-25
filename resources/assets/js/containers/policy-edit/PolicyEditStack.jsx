import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';
import { composeWithDevTools } from 'redux-devtools-extension';

import reducer from './../../reducers/policy-edit';
import PolicyEditContainer from './PolicyEditContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk)
);

export default class PolicyEditStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <PolicyEditContainer />
      </Provider>
    )
  }
}