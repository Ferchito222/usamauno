import { User } from './../../types';

export const userList = (api, userType, exportUrl) => (
  (dispatch) => {
    dispatch(_userList(api, userType));
    dispatch(setExportUrlBase(exportUrl));
  }
);

export const _userList = (api, userType) => ({
  type: User.LIST,
  payload: {
    api,
    userType
  }
});

export const filter = (query) => (
  (dispatch) => {
    dispatch(_filter(query));
    dispatch(setExportUrlFull(query));
  }
);

export const _filter = (query) => ({
  type: User.FILTER,
  payload: {
    query,
  }
});

export const setUser = (id) => ({
  type: User.SET_USER,
  payload: {
    id,
  }
});

export const userCreate = (user, query) => (
  (dispatch) => {
    dispatch(_userCreate(user));
    dispatch(_filter(query));    
  }
);

export const _userCreate = (user) => ({
  type: User.USER_CREATE,
  payload: {
    user,
  }
});


export const userEdit = (user, query) => (
  (dispatch) => {
    dispatch(_userEdit(user));
    dispatch(_filter(query));
  }
);

export const _userEdit = (user) => ({
  type: User.USER_EDIT,
  payload: {
    user,
  }
});

export const userActive = (id, active, query) => (
  (dispatch) => {
    dispatch(_userActive(id, active));
    dispatch(_filter(query));
  }
);

export const _userActive = (id, active) => ({
  type: User.USER_ACTIVE,
  payload: {
    id,
    active,
  }
});

export const setExportUrlBase = (url) => ({
  type: User.SET_EXPORT_URL_BASE,
  payload: {
    url,
  }
});

export const setExportUrlFull = (query) => ({
  type: User.SET_EXPORT_URL_FULL,
  payload: {
    query,
  }
});

export const passwordTurnOff = (id, turnOff, query) => (
  (dispatch) => {
    dispatch(_passwordTurnOff(id, turnOff));
    dispatch(_filter(query));
  }
);

export const _passwordTurnOff = (id, turnOff) => ({
  type: User.PASSWORD_TURN_OFF,
  payload: {
    id,
    turnOff,
  }
});

export const setReset = (reset) => ({
  type: User.RESET,
  payload: {
    reset
  }
});

export const passwordEdit = (id, query) => (
  (dispatch) => {
    dispatch(_passwordEdit(id));
    dispatch(_filter(query));
  }
);

export const _passwordEdit = (id) => ({
  type: User.PASSWORD_EDIT,
  payload: {
    id
  }
});