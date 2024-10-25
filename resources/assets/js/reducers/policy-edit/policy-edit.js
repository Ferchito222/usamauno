import { Map, fromJS } from 'immutable';
import { PolicyEdit } from './../../types';
import moment from 'moment';
import _ from 'underscore';

const initialState = Map({
  headers: [],
  details: [],
  lists: [],
  users: [],
  products: {},
  header: {},
  pagination: Map({
    total: 0,
    lastPage: 0,
    currentPage: 1,
    perPage: 30,
  }),
  filter: false,
});

const policyEditReducer = (state = initialState, action) => {
  switch (action.type) {
    case PolicyEdit.SET_DATA: {
      const { api, users, products } = action.payload;
      const { headers, details } = api;
      const pagination = state.get('pagination');

      const total = headers.length;
      const lastPage = _.chunk(headers, pagination.get('perPage')).length;
      const lists = headers.slice(0, (pagination.get('perPage') * pagination.get('currentPage')));
      
      return state.merge({
        headers,
        details,
        lists,
        users,
        products,
        pagination: {
          ...pagination.toJS(),
          total,
          lastPage,
        }
      });
    }

    case PolicyEdit.FETCH_DATA: {
      const { page } = action.payload;
      const pagination = state.get('pagination');

      if (page > pagination.get('lastPage') || page <= 0) {
        return state;
      }

      const headers = state.get('headers');
      let start = 0;
      let end = 0;

      if (page > pagination.get('currentPage')) {
        start = pagination.get('currentPage') * pagination.get('perPage');
        end = page * pagination.get('perPage');
      } else if (page < pagination.get('currentPage')) {
        start = (page - 1) * pagination.get('perPage');
        end = page * pagination.get('perPage');
      }
      
      const lists = headers.slice(start, end);

      return state.merge({
        lists,
        pagination: {
          ...pagination.toJS(),
          currentPage: page,
        }
      });
    }

    case PolicyEdit.FILTER_DATA: {
      const { formData } = action.payload;
      const headers = state.get('headers');
      const details = state.get('details');

      let filter = false;

      const lists = headers.filter(header => {
        filter = false;

        header.get('details').map(d => {
          if (header.get('issueNumber').includes(formData.number)
            && header.get('userName').includes(formData.user.toLowerCase())
            && details.get(d).get('client').get('fullName').toLowerCase().includes(formData.client.toLowerCase())
            && details.get(d).get('client').get('dni').includes(formData.dni)
            && moment(header.get('dateIssue'), 'YYYY-MM-DD').isBetween(formData.dateBegin, formData.dateEnd, 'day', '[]')
          ) {
            filter = true;
          }
        });

        return filter;
      });

      return state.merge({
        lists,
        filter: true,
      });
    }

    case PolicyEdit.RESET_PAGINATION: {
      const headers = state.get('headers');
      const pagination = state.get('pagination');
      const lists = headers.slice(0, (pagination.get('perPage')));
      
      return state.merge({
        lists,
        filter: false,
        pagination: {
          ...pagination.toJS(),
          currentPage: 1,
        },
      });
    }

    case PolicyEdit.SET_HEADER: {
      const { id } = action.payload;
      const headers = state.get('headers');
      const details = state.get('details');

      const index = headers.findIndex(h => h.get('idx') === id);
      const header = headers.get(index).toJS();
      const headerDetails = [];

      header.coverage = header.coverageIdx;
      header.creditProduct = header.creditProductIdx;

      header.details.map((d, index) => {
        header.details[index] = details.get(d).toJS();
      });

      return state.set('header', header);
    }

    case PolicyEdit.UPDATE_HEADER: {
      const { api } = action.payload;
      let headers = state.get('headers');
      let details = state.get('details');
      
      const idxHeader = headers.findIndex(h => h.get('idx') === api.header.idx);
      headers = headers.update(idxHeader, value => fromJS(api.header));

      for (const [key, d] of Object.entries(api.details)) {
        details = details.update(key, value => fromJS(d));
      }

      return state.merge({
        headers,
        details,
      });
    }

    default:
      return state;
  }
}

export default policyEditReducer;