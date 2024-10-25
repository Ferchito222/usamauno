import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { createStore, applyMiddleware } from 'redux';
import ReduxThunk from 'redux-thunk';
import { Map } from 'immutable';

import reducer from './../../reducers/email';
import EmailContainer from './EmailContainer';

const store = createStore(
  reducer,
  Map(),
  applyMiddleware(ReduxThunk)
);

export default class EmailStack extends Component {
  render() {
    return (
      <Provider store={store}>
        <EmailContainer />
      </Provider>
    )
  }
}