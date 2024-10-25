import { Map, List } from 'immutable';
import moment from 'moment';
import { Cancellation } from './../../types';

const initialState = Map({
  headers: List(),
  details: List(),
  header: Map(),
});

const cancellationReducer = (state = initialState, action) => {
  switch (action.type) {
    case Cancellation.LIST: {
      
      const { api } = action.payload;
      return state.merge({
        headers: api.headers,
        details: api.details,
      });
    }
    
    case Cancellation.SET_HEADER: {
      const { id } = action.payload;
      const headers = state.get('headers');
      
      const index = headers.findIndex(h => h.get('idx') === id);
      const header = headers.get(index);
      
      return state.set('header', header);
    }

    case Cancellation.CANCEL: {
      const { id } = action.payload;
      let headers = state.get('headers');

      const idxH = headers.findIndex(h => h.get('idx') === id);
      
      headers = headers.remove(idxH);

      return state.merge({
        headers,
      });
    }
  
    default:
      return state;
  }
}

export default cancellationReducer;