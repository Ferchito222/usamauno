import { Map } from 'immutable';
import { User } from './../../types';

const initialState = Map({
  base: '',
  fullPath: '',
});

const exportUrlReducer = (state = initialState, action) => {
  switch (action.type) {
    case User.SET_EXPORT_URL_BASE: {
      const { url } = action.payload;

      return state.merge({
        base: url,
        fullPath: url,
      });
    }
    
    case User.SET_EXPORT_URL_FULL: {
      const { query } = action.payload;
      const base = state.get('base');
      let fullPath = base;

      if (! /^\s*$/.test(query)) {
        fullPath += `?query=${query}`;
      }

      return state.set('fullPath', fullPath);
    }

    default:
      return state;
  }
}

export default exportUrlReducer;