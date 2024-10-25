import { Map, fromJS, List } from 'immutable';
import { AssignmentWallet } from './../../types';

const initialState = Map({
  usersFrom: List(),
  usersTo: List(),
  headers: List(),
  records: List(),
});

const assignmentWalletReducer = (state = initialState, action) => {
  switch (action.type) {
    case AssignmentWallet.SET_USERS: {
      const usersFrom = fromJS(action.payload.users);
      const headers = fromJS(action.payload.headers);
      
      return state.merge({
        usersFrom,
        headers,
      });
    }

    case AssignmentWallet.GET_CERTIFICATES: {
      const { user } = action.payload;
      const usersFrom = state.get('usersFrom');

      let usersTo = initialState.get('usersTo');
      let records = initialState.get('records');

      if (user) {
        usersTo = usersFrom.filter(u => u.get('id') !== user.id);
        records = fromJS(user.headers);
      }

      return state.merge({
        usersTo,
        records,
      });
    }

    case AssignmentWallet.ASSIGNMENT: {
      const { userFrom, userTo, certificates } = action.payload;
      let usersFrom = state.get('usersFrom');

      const indexFrom = usersFrom.findIndex(u => u.get('id') === userFrom);
      const indexTo = usersFrom.findIndex(u => u.get('id') === userTo);
      
      const hFrom = usersFrom.getIn([indexFrom, 'headers']).filter(h => ! certificates.includes(h));
      const hTo = usersFrom.getIn([indexTo, 'headers']).concat(certificates);

      usersFrom = usersFrom.setIn([indexFrom, 'headers'], hFrom);
      usersFrom = usersFrom.setIn([indexTo, 'headers'], hTo);

      return state.merge({
        usersFrom,
        usersTo: initialState.get('usersTo'),
        records: initialState.get('records'),
      });
    }

    default:
      return state;
  }
}

export default assignmentWalletReducer;