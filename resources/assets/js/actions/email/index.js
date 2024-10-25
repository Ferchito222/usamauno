import { Email } from './../../types';

export const list = (api) => ({
  type: Email.LIST,
  payload: {
    api,
  }
});

export const setEmail = (id) => ({
  type: Email.SET_EMAIL,
  payload: {
    id,
  }
});

export const emailCreate = (email) => ({
  type: Email.EMAIL_CREATE,
  payload: {
    email,
  }
});

export const emailEdit = (email) => ({
  type: Email.EMAIL_EDIT,
  payload: {
    email,
  }
});

export const emailDelete = (id) => ({
  type: Email.EMAIL_DELETE,
  payload: {
    id,
  }
});

export const emailTurnOff = (id, pivotId, turnOff) => ({
  type: Email.EMAIL_TURN_OFF,
  payload: {
    id,
    pivotId,
    turnOff,
  }
});