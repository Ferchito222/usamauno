import { CreditLine } from './../../types';

export const creditLineStore = (creditLine) => ({
  type: CreditLine.CREDIT_LINE_STORE,
  payload: {
    creditLine,
  }
});

export const creditLineConfirm = (confirm) => ({
  type: CreditLine.CREDIT_LINE_CONFIRM,
  payload: {
    confirm,
  }
});

export const creditLineRemove = () => ({
  type: CreditLine.CREDIT_LINE_REMOVE,
});