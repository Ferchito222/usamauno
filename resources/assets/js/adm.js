import React from 'react';
import ReactDOM from 'react-dom';
import 'es6-promise/auto';
import axios from 'axios';

import App from './containers/Adm.jsx';

import './css/home.styl';
import './css/onOffSwitch.styl';

window.axios = axios;

axios.interceptors.request.use(function (config) {
  config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  config.headers['X-Requested-With'] = 'XMLHttpRequest';

  return config;
});

ReactDOM.render(
  <App />,
  document.getElementById('adm-flux')
);