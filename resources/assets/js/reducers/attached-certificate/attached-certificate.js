import { Map, List } from 'immutable';
import moment from 'moment';
import { AttachedCertificate } from './../../types';

const initialState = Map({
  headers: List(),
  details: List(),
  lists: List(),
});

const AttachedCertificateReducer = (state = initialState, action) => {
  switch (action.type) {
    case AttachedCertificate.SET_DATA: {
      const { headers, details } = action.payload.api;

      return state.merge({
        headers,
        details,
      });
    }

    case AttachedCertificate.FILTER_DATA: {
      const { formData } = action.payload;
      const headers = state.get('headers');
      const details = state.get('details');

      let filter = false;

      let lists = headers.filter(header => {
        filter = false;

        header.get('details').map(d => {
          if (header.get('issueNumber').includes(formData.number)
            && (header.get('userName').includes(formData.user.toLowerCase()) || header.get('userFullName').includes(formData.user.toLowerCase()))
            && details.get(d).get('clientFullName').toLowerCase().includes(formData.client.toLowerCase())
            && moment(header.get('dateIssue'), 'YYYY-MM-DD').isBetween(formData.dateBegin, formData.dateEnd, 'day', '[]')
          ) {
            filter = true;
          }
        });

        return filter;
      });
      
      const isEmpty = Object.values(formData).every(x => (x === null || x === ''));

      if (isEmpty) {
        lists = initialState.get('lists');
      }

      return state.set('lists', lists);
    }

    case AttachedCertificate.FILE_DELETE: {
      const { id } = action.payload;
      const headers = state.get('headers');

      const index = headers.findIndex(h => h.get('idx') === id);

      return state.removeIn(['headers', index]);
    }
  
    default:
      return state;
  }
}

export default AttachedCertificateReducer;