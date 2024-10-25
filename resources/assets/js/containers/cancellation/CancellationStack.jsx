import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';

import reducer from './../../reducers/cancellation';
import CancellationContainer from './CancellationContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk)
);

export default class CancellationStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <CancellationContainer />
      </Provider>
    )
  }
}