<template lang="pug">
  div(v-if="mc != null")
    h5(class=['text-center'])
      span(class="oi oi-plus")
      |  Asignación de Cuestionarios / Preguntas
      br
      br
      span(class=['badge', 'badge-secondary']) {{ mc.name }}
    
    div(class=['alert', 'alert-warning'], role="alert")
      | Si Modifica la asignacion de CUESTIONARIOS se debe reasignar las preguntas.
    
    div(class=['card'], style={fontSize: '120%'})
      div(class=['card-body'])
        div(class=['row'])
          div(class=['col-sm-8'])
            h6(class=['card-title']) Cuestionarios:
          div(class=['col-sm-4', 'text-center'])
            button(type="button",
              v-on:click.prevent="assign = ! assign",
              class=['btn', 'btn-sm'],
              :class="assign ? 'btn-danger' : 'btn-success'",
              style={marginBottom: '5px'}
            ) {{ assign ? 'Cancelar Asignación de Preguntas' : 'Asignar Preguntas' }}
        
        form(v-on:submit.prevent="updateQuestionnaires($event)")
          ul(class=['list-group', 'list-group-flush'])
            li(v-for="(questionnaire, index) in questionnaires",
              :key="index",
              class=['list-group-item'],
              v-if="formData.questionnaires[questionnaire.id].selected || ! assign",
              :class="formData.questionnaires[questionnaire.id].selected ? 'list-group-item-success' : ''"
            )
              div(class=['row'], style={paddingBottom: '3px'})
                div(class=['col-sm-1'], style={textAlign: 'center'})
                  toggle-button(v-model="formData.questionnaires[questionnaire.id].selected",
                    v-if="! assign",
                    color="#82c7eb",
                    :sync="true",
                    class="changed-font"
                  )
                div(class=['col-sm-1'])
                  span(class=['badge', 'badge-primary', 'badge-pill']) {{ index + 1 }}
                div(class=['col-sm-7'])
                  | {{ questionnaire.title }}
                div(class=['col-sm-3'])
                  toggle-button(v-model="formData.questionnaires[questionnaire.id].enabled",
                    v-if="! assign",
                    :disabled="! formData.questionnaires[questionnaire.id].selected",
                    color="#54d378",
                    :sync="true",
                    class="changed-font",
                    :width="120",
                    :height="25", :labels="{checked: 'Habilitado', unchecked: 'Deshabilitado'}"
                  )
                  div(v-if="assign")
                    span(class="oi oi-grid-three-up")
                    |  
                    strong {{ getNumberQuestions(questionnaire.id) }} Preguntas 
              question-assignment(v-if="assign", :questions="questions")
          div(class=['row'])
            div(class=['col', 'text-center'], style={paddingTop: '10px'})
              button(type="submit", class="btn btn-primary btn-sm") Guardar
              |  
              router-link(:to="{ name: 'mc-list' }", class="btn btn-secondary btn-sm")
                span(class="oi oi-media-skip-backward")
                |  Regresar

</template>

<script>
import QuestionAssignment from './QuestionAssignment.vue';

export default {
  components: {
    QuestionAssignment
  },
  data: () => {
    return {
      mc: null,
      questionnaires: [],
      formData: {
        questionnaires: []
      },
      assign: false,
      questions: []
    }
  },
  computed: {
    _() {
      return _;
    }
  },
  created() {
    this.getQuestionnaires();
  },
  methods: {
    getQuestionnaires: function () {
      this.eLoading('body', true);

      let id  = this.$route.params.id;
      let url = $('#r-mc-questionnaires-edit').prop('value').replace(':id', id);

      axios.get(url)
        .then(response => {
          this.eLoading('body', false);

          if (response.status === 200) {
            this.mc             = response.data.mc;
            this.questionnaires = response.data.questionnaires;
            this.questions      = response.data.questions;
            let mc_qts          = _.pluck(this.mc.questionnaires, 'id');
            let mc_qts_enableds = _.pluck(_.filter(this.mc.certificate_questionnaires, mc_cq => (mc_cq.active == true)), 'mc_questionnaire');

            this.questionnaires.forEach(qt => {
              let selected = false;
              let enabled  = false;
              
              if (mc_qts.includes(qt.id)) {
                selected = true;

                if (mc_qts_enableds.includes(qt.id)) {
                  enabled  = true;
                }
              }
              
              this.$set(this.formData.questionnaires, [qt.id], { selected: selected, enabled: enabled });
            });
          }
        })
        .catch(e => {
          this.eLoading('body', false);
          console.info(e);
        });
    },

    updateQuestionnaires: function (event) {
      this.eLoading('body', true);

      let id  = this.$route.params.id;
      let url = $('#r-mc-questionnaires-update').prop('value').replace(':id', id);

      axios.put(url, this.formData)
        .then(response => {
          this.eLoading('body', false);

          if (response.status === 200) {
            let data = response.data;

            if (data.success) {
              this.$set(this, 'mc', data.mc);

              this.$notify({
                group: 'mc',
                type: 'success',
                title: 'Mensaje importante',
                text: data.message,
              });
            }
          }
        })
        .catch(e => {
          this.eLoading('body', false);

          if (e.response) {
            if (e.response.data.warning) {
              this.$notify({
                group: 'mc',
                type: 'warn',
                title: 'Mensaje importante',
                text: e.response.data.message,
              });
            }
          } else if (e.request) {
            // console.log(e.request);
          } else {
            console.log('Error', e.message);
          }

          // console.log(e.config);
        });
    },

    getNumberQuestions: function (q_id) {
      let number = 0;
      let mc_cq  = _.first(_.filter(this.mc.certificate_questionnaires, (mc_cq) => ( mc_cq.mc_questionnaire == q_id )));

      if (mc_cq != undefined) {
        number = mc_cq.questions.length;
      }

      return number;
    }
  }
}
</script>

<style lang="stylus">
.list-group-item-success
  background-color #edf9ff

.vue-js-switch.changed-font
  font-size 90%

label
  margin-bottom 0

.btn
  cursor pointer

.btn-danger
  background-color #e8636e
</style>