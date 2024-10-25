import React, { Component } from 'react';
import { NotificationContainer, NotificationManager } from 'react-notifications';
import { eLoading } from 'helpers';

export default class UserImport extends Component {
  constructor(props) {
    super(props);
    this.state = {
      file: '',
      errors: {},
      urlList: document.getElementById('ru-list').value,
    };
    this.file = React.createRef();

    this.handleFileUpload = this.handleFileUpload.bind(this);
    this.submitFile = this.submitFile.bind(this);
  }
  

  handleFileUpload = () => {
    const files = this.file.current.files;
    let fileName = '';
    
    if (files && files.length === 1) {
      this.setState({ file: files[0] });
      fileName = files[0].name;

      document.getElementById('Input-fileText').innerHTML = fileName;
    }
  }

  submitFile = () => {
    eLoading(true);

    const $url = document.getElementById('ru-import').value;

    const { file, urlList } = this.state;
    const formData = new FormData();
    formData.append('file', file);

    axios.post($url, formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    ).then(response => {
      eLoading(false);

      if (response.data.success) {
        NotificationManager.success(response.data.message, 'Mensaje importante');
        
        setTimeout(() => {
          window.location.href = urlList;
        }, 1000);
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
      });
  }

  render() {
    const { errors, urlList } = this.state;

    return (
      <div>
        <div className="Grid-container">
          <div className="Grid-header">
            <h5>Carga masiva de usuarios</h5>
          </div>
          <div className="Grid-box">
            <form style={{ padding: '10px' }}>
              <input
                className="Input-file Input-file1"
                id="file"
                type="file"
                name="file"
                ref={this.file}
                onChange={this.handleFileUpload}
              />
              <label htmlFor="file">
                <figure>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                    <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" />
                  </svg>
                </figure>
                <span id="Input-fileText">Escoge un archivo...</span>
              </label>

              {errors.file && <div className="Text-error">{errors.file[0]}</div>}

              <br />
              <button className="Btn Btn-sm Btn-success" type="button" onClick={this.submitFile}>
                Importar
              </button>
              <a className="Btn Btn-sm Btn-spaceLeft" href={urlList}>Cancelar</a>
            </form>
          </div>
        </div>

        <NotificationContainer />
      </div>
    )
  }
}