import React, { PureComponent } from 'react';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import { eLoading, closeModal } from 'helpers';

export class CancellationFormView extends PureComponent {
  constructor(props) {
    super(props);
    this.state = {
      reason: '',
      errors: {},
    };
  }

  handleInputChange = (event) => {
    const target = event.target;
    const value = target.type === 'checkbox' ? target.checked : target.value;
    const name = target.name;

    this.setState({ [name]: value });
  }

  store = (e, idx) => {
    e.preventDefault();
    eLoading(true);

    const { reason } = this.state;
    const { product } = this.props;
    const $url = document.getElementById('roc-store').value.replace(':rp_id', product).replace(':header_id', idx);

    axios.post($url, { reason })
      .then(response => {
        eLoading(false);
        this.setState({ errors: {} });

        if (response.status === 200) {
          const data = response.data;

          if (data.success) {
            this.props.handleCancel(idx);
            closeModal();
            NotificationManager.success(data.message, 'Mensaje importante');
          }
        }
      })
      .catch(e => {
        eLoading(false);

        if (e.response) {
          this.setState({ errors: e.response.data });

          if (e.response.data.warning) {
            NotificationManager.warning(e.response.data.message, 'Mensaje importante');
          }
        } else if (e.request) {
          // console.log(e.request);
        } else {
          // console.log('Error', e.message);
        }

        // console.log(e);
      });
  }

  render() {
    const { header } = this.props;
    const { reason, errors } = this.state;

    return (
      <div>
        <h3>
          Formulario de Anulación: Certificado Nº {header.get('prefix')}-{header.get('issueNumber')}
        </h3>

        <form onSubmit={(e) => this.store(e, header.get('idx'))}>
          <fieldset>
            <div className="Box">
              <div className="Box-col">
                <textarea
                  name="reason"
                  value={reason}
                  onChange={this.handleInputChange}
                  rows="5"
                  cols="5"
                  placeholder="Motivo de Anulación"
                />
                {errors.reason && <div className="Text-error">{errors.reason[0]}</div>}
              </div>
            </div>
          </fieldset>

          <div className="Box">
            <div className="Box-col" style={{ textAlign: 'center' }}>
              <button type="submit" className="Btn-sm Btn-primary">
                Anular Certificado <i className="material-icons">cancel</i>
              </button>
            </div>
          </div>
        </form>
      </div>
    )
  }
}
