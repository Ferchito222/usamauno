import React, { Component } from 'react';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';

import reducer from './../../reducers/attached-certificate';
import AttachedCertificateContainer from './AttachedCertificateContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk)
);

export default class AttachedCertificateStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <AttachedCertificateContainer />
      </Provider>
    )
  }
}
