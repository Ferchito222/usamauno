import { Map, fromJS } from 'immutable';
import { CreditLine } from './../../types';

const initialState = Map({
  creditLine: Map(),
});

const creditLineReducer = (state = initialState, action) => {
  switch (action.type) {
    case CreditLine.CREDIT_LINE_STORE: {
      const { creditLine } = action.payload;

      return state.set('creditLine', fromJS(creditLine));
    }
    
    case CreditLine.CREDIT_LINE_CONFIRM: {
      const { confirm } = action.payload;

      return state.setIn(['creditLine', 'confirmed'], confirm);
    }
    
    case CreditLine.CREDIT_LINE_REMOVE: {
      return state.set('creditLine', Map());
    }
  
    default:
      return state;
  }
};

export default creditLineReducer;