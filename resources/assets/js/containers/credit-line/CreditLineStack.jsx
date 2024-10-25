import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';

import reducer from './../../reducers/credit-line';
import CreditLineContainer from './CreditLineContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk)
);

export default class CreditLineStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <CreditLineContainer />
      </Provider>
    )
  }
}
