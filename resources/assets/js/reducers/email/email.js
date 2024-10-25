import { Map, fromJS } from 'immutable';
import { Email } from './../../types';

const initialState = Map({
  emails: [],
  products: {},
  email: null,
});

const emailReducer = (state = initialState, action) => {
  switch (action.type) {
    case Email.LIST: {
      const { api } = action.payload;

      return state.merge({
        emails: api.emails,
        products: api.products,
      });
    }

    case Email.SET_EMAIL: {
      const { id } = action.payload;
      let email = null;

      if (id) {
        const emails = state.get('emails');
        const index = emails.findIndex(e => e.get('idx') === id);

        email = emails.get(index);
      }

      return state.set('email', email);
    }

    case Email.EMAIL_CREATE: {
      const email = fromJS(action.payload.email);

      return state.updateIn(['emails'], list => list.push(email));
    }

    case Email.EMAIL_EDIT: {
      const email = fromJS(action.payload.email);

      const index = state.get('emails').findIndex(e => e.get('idx') === email.get('idx'));

      return state.setIn(['emails', index], email);
    }

    case Email.EMAIL_DELETE: {
      const { id } = action.payload;

      const index = state.get('emails').findIndex(e => e.get('idx') == id);

      return state.removeIn(['emails', index]);
    }

    case Email.EMAIL_TURN_OFF: {
      const { id, pivotId, turnOff } = action.payload;

      const idxEmail = state.get('emails').findIndex(e => e.get('idx') === id);
      const idxRp = state.get('emails').get(idxEmail).get('products').findIndex(p => p.get('id') === pivotId);

      return state.setIn(['emails', idxEmail, 'products', idxRp, 'active'], turnOff);
    }

    default:
      return state;
  }
}

export default emailReducer;