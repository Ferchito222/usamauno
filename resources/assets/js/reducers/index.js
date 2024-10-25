import { combineReducers } from "redux";
import creditLine from './credit_line';
import cancellation from './cancellation';

const reducerApp = combineReducers({
  creditLine,
  cancellation
});

export default reducerApp;