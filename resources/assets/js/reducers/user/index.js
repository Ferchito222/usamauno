import { combineReducers } from 'redux-immutable';
import user from './user';
import exportUrl from './export-url';

export default combineReducers({
  user,
  exportUrl,
});