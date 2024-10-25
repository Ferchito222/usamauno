import { AttachedCertificate } from './../../types';

export const setData = (api) => ({
  type: AttachedCertificate.SET_DATA,
  payload: {
    api
  }
});

export const filterData = (formData) => ({
  type: AttachedCertificate.FILTER_DATA,
  payload: {
    formData
  }
});

export const fileDelete = (id, formData) => (
  (dispatch) => {
    dispatch(_fileDelete(id));
    dispatch(filterData(formData));
  }
);

export const _fileDelete = (id) => ({
  type: AttachedCertificate.FILE_DELETE,
  payload: {
    id
  }
});