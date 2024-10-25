<template lang="pug">
  
  div
    .Grid-container
      .Grid-header
        h5 Ajustes del Sistema
      .Grid-action
        a(href="#Modal-setting", class=['Btn', 'Btn-primary'], v-on:click="settingCreate($event)", v-if="user_type === 'ADT'")
          span(class=['oi'])&attributes({'data-glyph': 'plus'})
          | Agregar
      .Grid-main
        table(class=['Table', 'Table-hover', 'Table-striped', 'Table-mcTeal'])
          thead
            tr
              th Nombre
              th Modelo
              th Código
              th Valor
              th(style={'width': '300px'}) Mensaje
              th
              th(v-if="user_type === 'ADT'") Log
          tbody
            tr(v-for="(setting, index) in settings", :key="index")
              td
                strong {{ setting.name }}
              td {{ setting.model }}
              td
                span(style={color: '#0e90d2'}) {{ setting.codename }}
              td
                strong {{ setting.logs != null ? _.last(setting.logs).value : '' }}
              td
                span(style={fontWeight: 'normal'})
                  | {{ setting.logs != null ? _.last(setting.logs).change_message : '' }}
              td
                a(href="#Modal-setting", class=['Btn', 'Btn-icon'], v-on:click="settingEdit($event, setting, index)")
                  span(class=['oi'])&attributes({'data-glyph': 'pencil'})
              td(v-if="user_type === 'ADT'")
                span(v-tooltip="{ content: JSON.stringify(setting.logs) }") Logs

    #Modal-setting.Modal-dialog
      .Modal-content
        a(href='#close', ref="modalClose", class=['Modal-close'], title='Cerrar') X
        .Modal-body
          h4(class=['Modal-title']) {{ edit ? 'Editar' : 'Crear' }} Configuración

          form(v-on:submit.prevent="settingUpdate($event)")
            fieldset
              .Box
                .Box-col6
                  label Nombre :
                  input(type="text", v-model="formData.name", autocomplete="off", v-if="! edit")
                  .Text-error(v-if="errors.name") {{ errors.name[0] }}
                  label(class=['Label-dark'], v-if="edit")
                    strong {{ setting.name }}

                  label Modelo :
                  input(type="text", v-model="formData.model", autocomplete="off", v-if="! edit")
                  .Text-error(v-if="errors.model") {{ errors.model[0] }}
                  label(class=['Label-dark'], v-if="edit")
                    strong {{ setting.model }}

                  label Código :
                  input(type="text", v-model="formData.codename", autocomplete="off", v-if="! edit")
                  .Text-error(v-if="errors.codename") {{ errors.codename[0] }}
                  label(class=['Label-dark'], v-if="edit")
                    strong {{ setting.codename }}
                .Box-col6
                  label Valor :
                  input(type="text", v-model="formData.value", autocomplete="off")
                  .Text-error(v-if="errors.value") {{ errors.value[0] }}
                  
                  label Mensaje :
                  input(type="text", v-model="formData.change_message", autocomplete="off")
                  .Text-error(v-if="errors.change_message") {{ errors.change_message[0] }}
              .Box
                .Box-col(style={textAlign: 'center'})
                  button(type="submit", class=['Btn', 'Btn-success', 'Btn-sm']) Guardar

    notifications(group="setting")

</template>

<script>
export default {
  data: function() {
    return {
      settings: [],
      user_type: '',
      setting: {},
      index: 0,
      formData: {},
      errors: {},
      edit: false,
    }
  },
  computed: {
    _() {
      return _;
    }
  },
  created() {
    this.getSettings();
  },
  methods: {
    getSettings: function() {
      this.eLoading(true);

      let url = $('#route-setting-list').prop('value');

      axios.get(url)
        .then(response => {
          this.eLoading(false);

          if (response.status === 200) {
            this.settings  = response.data.settings;
            this.user_type = response.data.user_type;
          }
        })
        .catch(e => {
          console.info(e);
        });
    },

    beforeOpen() {
      this.formData = {};
      this.errors   = {};

      this.$set(this.formData, 'change_message', '');
    },

    settingCreate: function (event) {
      this.edit = false;
      
      this.beforeOpen();
    },

    settingEdit: function(event, setting, index) {
      this.index   = index;
      this.setting = setting;
      this.edit    = true;
      
      this.beforeOpen();
    },

    settingUpdate: function(event) {
      this.eLoading('#setting-edit', true);

      let url            = $('#route-setting-update').prop('value');
      this.formData.edit = this.edit;

      if (this.edit) {
        this.formData.id = this.setting.id;
      }

      axios.put(url, this.formData)
        .then(response => {
          this.eLoading('#setting-edit', false);

          this.formData = {};
          this.errors = {};

          if (response.status === 200) {
            let data = response.data;

            if (data.success) {
              this.$notify({
                group: 'setting',
                type: 'success',
                title: 'Mensaje importante',
                text: data.message,
              });

              if (this.edit) {
                this.$set(this.settings, this.index, data.setting);
              } else {
                this.settings.push(data.setting);
              }

              this.$refs.modalClose.click();
            }
          }
        })
        .catch(e => {
          this.eLoading('#setting-edit', false);

          if (e.response) {
            this.errors = e.response.data;
          } else if (e.request) {
          } else {
            console.log('Error', e.message);
          }
        });
    },

  }
}
</script>

<style>
.vue-tooltip {
  background-color: #000;
  box-sizing: border-box;
  color: #fff;
  max-width: 320px;
  padding: 6px 10px;
  border-radius: 3px;
  z-index: 100;
  box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.4);
  font-size: 12px;
}

.vue-tooltip .vue-tooltip-content {
  text-align: justify;
}

.vue-tooltip .tooltip-arrow {
  content: '';
  width: 0;
  height: 0;
  border-style: solid;
  position: absolute;
  margin: 5px
}

.vue-tooltip[x-placement^="top"] {
  margin-bottom: 5px
}

.vue-tooltip[x-placement^="top"] .tooltip-arrow {
  border-width: 5px 5px 0 5px;
  border-top-color: #000;
  border-bottom-color: transparent !important;
  border-left-color: transparent !important;
  border-right-color: transparent !important;
  bottom: -5px;
  margin-top: 0;
  margin-bottom: 0
}

.vue-tooltip[x-placement^="bottom"] {
  margin-top: 5px
}

.vue-tooltip[x-placement^="bottom"] .tooltip-arrow {
  border-width: 0 5px 5px 5px;
  border-bottom-color: #000;
  border-top-color: transparent !important;
  border-left-color: transparent !important;
  border-right-color: transparent !important;
  top: -5px;
  margin-top: 0;
  margin-bottom: 0
}

.vue-tooltip[x-placement^="right"] {
  margin-left: 5px
}

.vue-tooltip[x-placement^="right"] .tooltip-arrow {
  border-width: 5px 5px 5px 0;
  border-right-color: #000;
  border-top-color: transparent !important;
  border-left-color: transparent !important;
  border-bottom-color: transparent !important;
  left: -5px;
  margin-left: 0;
  margin-right: 0
}

.vue-tooltip[x-placement^="left"] {
  margin-right: 5px
}

.vue-tooltip[x-placement^="left"] .tooltip-arrow {
  border-width: 5px 0 5px 5px;
  border-left-color: #000;
  border-top-color: transparent !important;
  border-right-color: transparent !important;
  border-bottom-color: transparent !important;
  right: -5px;
  margin-left: 0;
  margin-right: 0
}
</style>

<style lang="stylus" scoped>
form
  width 600px
</style>