import React, { Component } from 'react';
import _ from 'underscore';

export class PolicyEditSearchView extends Component {
  constructor(props) {
    super(props);
    this.state = {
      formData: {
        product: '',
        ...this.initialState,
      },
    };
  }

  get initialState() {
    return {
      client: '',
      user: '',
      number: '',
      dni: '',
      dateBegin: '',
      dateEnd: '',
    };
  }

  componentDidMount = () => {
    const { products } = this.props;

    const product = _.keys(products.toJS())[0];
    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        product,
      }
    }));
  }

  handleInputChange = (event) => {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    const { formData } = this.state;
    formData[name] = value;
    this.setState({ formData: formData });

    this.setState({ formData }, () => this.props.handleFilterData(formData));
  }

  reset = () => {
    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        ...this.initialState,
      }
    }));

    this.props.handleResetPagination();
  }

  render() {
    const { formData } = this.state;
    const { products, users } = this.props;

    return (
      <form onSubmit={(e) => {e.preventDefault()}}>
        <fieldset>
          <div className="Box">
            <div className="Box-col6">
              <label>Producto:</label>
              <select name="product" value={formData.product} onChange={this.handleInputChange}>
                {
                  _.map(products.toJS(), (value, index) => {
                    return <option value={index} key={index}>{value}</option>
                  })
                }
              </select>
              
              <label>Cliente:</label>
              <input type="text" name="client" value={formData.client} onChange={this.handleInputChange} />
              
              <label>Usuario:</label>
              <select name="user" value={formData.user} onChange={this.handleInputChange}>
                <option value="">Todos...</option>
                {
                  users.map((u, index) => {
                    return <option value={u.get('username')} key={u.get('username')}>{u.get('full_name')}</option>
                  })
                }
              </select>
            </div>
            <div className="Box-col6">
              <label>Nº de Certificado:</label>
              <label className="Label-code">{formData.product.toUpperCase()}</label>
              <input type="text" name="number" value={formData.number} onChange={this.handleInputChange} />

              <label>Documento de Identidad:</label>
              <input type="text" name="dni" value={formData.dni} onChange={this.handleInputChange} />
              
              <label>Fecha de Emisión (Desde - Hasta):</label>
              <input
                type="date"
                name="dateBegin"
                value={formData.dateBegin}
                onChange={this.handleInputChange}
                className="Form-date"
                placeholder="Desde"
                style={{ width: '49%' }}
              />
              <input type="date"
                name="dateEnd"
                value={formData.dateEnd}
                onChange={this.handleInputChange}
                className="Form-date"
                placeholder="Hasta"
                style={{ width: '49%', marginLeft: '2%' }}
              />
            </div>
          </div>
          <div className="Box">
            <div className="Box-col" style={{textAlign: 'center'}}>
              <button className="Btn Btn-sm Btn-primary" type="button" onClick={this.reset}>Limpiar búsqueda</button>
            </div>
          </div>
        </fieldset>
      </form>
    )
  }
}
