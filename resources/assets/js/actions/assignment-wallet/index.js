import { AssignmentWallet } from './../../types';

export const setUsers = (users, headers) => ({
  type: AssignmentWallet.SET_USERS,
  payload: {
    users,
    headers,
  }
});

export const getCertificates = (user) => ({
  type: AssignmentWallet.GET_CERTIFICATES,
  payload: {
    user,
  }
});

export const assignment = (userFrom, userTo, certificates) => ({
  type: AssignmentWallet.ASSIGNMENT,
  payload: {
    userFrom,
    userTo,
    certificates,
  }
});