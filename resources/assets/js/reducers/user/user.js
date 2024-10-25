import { Map, fromJS } from 'immutable';
import { User } from './../../types';

const initialState = Map({
  users: [],
  lists: [],
  user: null,
  userType: null,
  reset: false,
});

const userReducer = (state = initialState, action) => {
  switch (action.type) {
    case User.LIST: {
      const { api, userType } = action.payload;

      return state.merge({
        users: api.users,
        lists: api.users,
        userType,
      });
    }

    case User.FILTER: {
      let { query } = action.payload;
      const users = state.get('users');
      let lists = users;

      if (! /^\s*$/.test(query)) {
        query = query.toLowerCase();

        lists = users.filter((user) => {
          return (user.get('userName').toLowerCase().includes(query)
            || user.get('fullName').toLowerCase().includes(query)
            || user.get('email').toLowerCase().includes(query)
            || (user.get('cityName') ? user.get('cityName').toLowerCase().includes(query) : false)
            || (user.get('agencyName') ? user.get('agencyName').toLowerCase().includes(query) : false)
            || user.get('profileName').toLowerCase().includes(query));
        });
      }

      return state.set('lists', lists);
    }

    case User.SET_USER: {
      const { id } = action.payload;
      
      let user = null;

      if (id) {
        const users = state.get('users');
        const index = users.findIndex(u => u.get('idx') === id);
        user = users.get(index);
      }

      return state.set('user', user);
    }

    case User.USER_CREATE: {
      const user = fromJS(action.payload.user);

      return state.updateIn(['users'], list => list.push(user));
    }
    
    case User.USER_EDIT: {
      const user = fromJS(action.payload.user);

      const index = state.get('users').findIndex(u => u.get('idx') === user.get('idx'));

      return state.setIn(['users', index], user);
    }

    case User.USER_ACTIVE: {
      const { id, active } = action.payload;

      const index = state.get('users').findIndex(user => user.get('idx') === id);

      return state.setIn(['users', index, 'active'], active);
    }

    case User.RESET: {
      const { reset } = action.payload;

      return state.set('reset', reset);
    }

    case User.PASSWORD_EDIT: {
      const { id } = action.payload;

      const index = state.get('users').findIndex(user => user.get('idx') === id);

      return state.setIn(['users', index, 'passwordChange'], false);
    }

    case User.PASSWORD_TURN_OFF: {
      const { id, turnOff } = action.payload;

      const index = state.get('users').findIndex(user => user.get('idx') === id);

      return state.setIn(['users', index, 'passwordChange'], turnOff);
    }

    default:
      return state;
  }
}

export default userReducer;