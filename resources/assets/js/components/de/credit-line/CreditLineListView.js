import React from 'react';

export const CreditLineListView = (props) => {
  const { creditLine, coverages, policyTypes } = props;

  return (
    <table className="table" style={{ fontSize: '80%' }}>
      <thead>
        <tr>
          <th>Número de Línea</th>
          <th>Documento de identidad</th>
          <th>Complemento</th>
          <th>Cobertura</th>
          <th>Participación</th>
          <th>Porcentaje Extra Prima</th>
          <th>Tasa base</th>
          <th>Tipo de Póliza</th>
          <th>Asegurado</th>
        </tr>
      </thead>
      <tbody>
        {
          creditLine.get('clients').map((client, index) => {
            return (
              <tr className={creditLine.get('confirmed') ? 'info' : 'warning'} key={index}>
                <th>{creditLine.get('numberLine')}</th>
                <th>{client.get('dni')}</th>
                <th>{client.get('complement')}</th>
                <th>{coverages[creditLine.get('coverage')]}</th>
                <th>{client.get('percentageCredit')} %</th>
                <th>{client.get('percentage')} %</th>
                <th>{client.get('rate')}</th>
                <th>{policyTypes[creditLine.get('policyType')].name}</th>
                <th>{client.get('insured') ? 'SI' : 'NO'}</th>
              </tr>
            );
          })
        }
      </tbody>
    </table>
  )
}