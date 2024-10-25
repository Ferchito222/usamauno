import React from 'react';

export const EmailListView = (props) => {
  const { emails, products } = props;

  return (
    <table className="Table Table-hover Table-striped Table-mcTeal" style={{ width: '80%' }}>
      <thead>
        <tr>
          <th>Correo Electrónico</th>
          <th>Nombre</th>
          <th>Productos Asignados</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        {
          emails.map((email, index) => {
            return (
              <tr key={index}>
                <td style={{ fontWeight: 600 }}>{email.get('email')}</td>
                <td>{email.get('name')}</td>
                <td>
                  <div className="Lists">
                    <ol>
                      {
                        email.get('products').map((product, index) => {
                          return (
                            <li key={index}>
                              <a
                                href="#"
                                onClick={(e) => props.handleEmailTurnOff(e, email.get('idx'), product.get('id'), product.get('active'))}
                                className={`${product.get('active') == 1 ? '' : 'List-disabled'}`}
                              >
                                {products.get(product.get('ad_retailer_product_id'))}
                              </a>
                            </li>
                          )
                        })
                      }
                    </ol>
                  </div>
                </td>
                <td style={{ verticalAlign: 'middle' }}>
                  <a className="Btn Btn-icon" href="#Modal-app" onClick={() => props.setView('EmailFormView', email.get('idx'))}>
                    <i className="material-icons">edit</i>
                  </a>
                  <a className="Btn Btn-icon Btn-spaceLeft" href="#" onClick={(e) => props.handleEmailDelete(e, email.get('idx'))}>
                    <i className="material-icons">cancel</i>
                  </a>
                </td>
              </tr>
            )
          })
        }
      </tbody>
    </table>
  )
}