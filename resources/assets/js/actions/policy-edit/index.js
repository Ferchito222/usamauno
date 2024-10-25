import { PolicyEdit } from './../../types';

export const setData = (api, users, products) => ({
  type: PolicyEdit.SET_DATA,
  payload: {
    api,
    users,
    products,
  }
});

export const fetchData = (page) => ({
  type: PolicyEdit.FETCH_DATA,
  payload: {
    page,
  }
});

export const filterData = (formData) => ({
  type: PolicyEdit.FILTER_DATA,
  payload: {
    formData,
  }
});

export const resetPagination = () => ({
  type: PolicyEdit.RESET_PAGINATION,
});

export const setHeader = (id) => ({
  type: PolicyEdit.SET_HEADER,
  payload: {
    id,
  }
});

export const updateHeader = (api) => (
  (dispatch) => {
    dispatch(_updateHeader(api));
    dispatch(resetPagination());
  }
);

export const _updateHeader = (api) => ({
  type: PolicyEdit.UPDATE_HEADER,
  payload: {
    api,
  }
});