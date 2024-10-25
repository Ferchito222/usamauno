import React, { PureComponent } from 'react';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import _ from 'underscore';
import { eLoading } from 'helpers';

export class EmailFormView extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {
      formData: {
        name: '',
        email: '',
        products: [],
      },
      errors: {},
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.emailSetUp = this.emailSetUp.bind(this);
  }

  componentDidMount = () => {
    const { email } = this.props;

    if (email) {
      const formData = {
        name: email.get('name'),
        email: email.get('email'),
        products: _.pluck(email.get('products').toJS(), 'ad_retailer_product_id'),
      };

      this.setState({ formData });
    }
  }
  
  
  handleInputChange = (event) => {
    const target = event.target;
    const name = target.name;
    let value = target.type === 'checkbox' ? target.checked : target.value;

    if (target.multiple) {
      value = [].slice.call(target.selectedOptions).map(o => o.value);
    }

    this.setState(prevState => ({
      formData: {
        ...prevState.formData,
        [name]: value,
      }
    }));
  }

  emailSetUp = (e) => {
    e.preventDefault();

    eLoading(true);

    const $url = document.getElementById('re-edit').value;
    let { formData } = this.state;
    const { email } = this.props;

    if (email) {
      formData.id = email.get('idx');
    }

    axios.put($url, formData)
      .then(response => {
        eLoading(false);

        if (response.status === 200) {
          const data = response.data;

          if (data.success) {
            if (email) {
              this.props.handleEmailSetUp(data.email, true);
            } else {
              this.props.handleEmailSetUp(data.email);
            }
            
            NotificationManager.success(data.message, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        eLoading(false);

        if (e.response) {
          this.setState({ errors: e.response.data });

          if (e.response.data.warning) {
            NotificationManager.success(e.response.data.message, 'Mensaje importante');
          }
        } else if (e.request) {
          // console.log(e.request);
        } else {
          // console.log('Error', e.message);
        }

        // console.log(e.config);
      });
  }

  render() {
    const { formData, errors } = this.state;
    const { email, products } = this.props;

    return (
      <div>
        <form onSubmit={this.emailSetUp}>
          <fieldset>
            <div className="Box">
              <div className="Box-col">
                <label>Nombre:</label>
                <input type="text" name="name" value={formData.name} onChange={this.handleInputChange} autoComplete="off" />
                {errors.name && <div className="Text-error">{errors.name[0]}</div>}
                
                <label>Email:</label>
                <input type="email" name="email" value={formData.email} onChange={this.handleInputChange} autoComplete="off" />
                {errors.email && <div className="Text-error">{errors.email[0]}</div>}

                <label>Productos:</label>
                <select name="products" value={formData.products} onChange={this.handleInputChange} multiple={true} style={{ height: '100px', overflow: 'auto' }}>
                  {
                    _.map(products.toJS(), (value, index) =>
                      <option value={index} key={index}>{value}</option>
                    )
                  }
                </select>
                {errors.products && <div className="Text-error">{errors.products[0]}</div>}
              </div>
            </div>

            <div className="Box">
              <div className="Box-col" style={{ textAlign: 'center' }}>
                <button className="Btn Btn-sm Btn-success" type="submit">Guardar</button>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    )
  }
}
