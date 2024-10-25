import { Cancellation } from './../../types';

export const list = (api) => ({
  type: Cancellation.LIST,
  payload: {
    api,
  }
});

export const setHeader = (id) => ({
  type: Cancellation.SET_HEADER,
  payload: {
    id,
  }
});

export const cancel = (id) => ({
  type: Cancellation.CANCEL,
  payload: {
    id,
  }
});