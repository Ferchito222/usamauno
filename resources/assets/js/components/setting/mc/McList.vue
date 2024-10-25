<template lang="pug">
  div
    h4(class=['text-center']) Certificado Médico
    br
    div(class=['row'])
      div(class=['col-12'], style={paddingBottom: '100px'})
        table(class=['table', 'table-sm', 'table-striped', 'table-hover', 'text-center'])
          thead(class=['thead-default'])
            tr
              th(class=['text-center']) Tipo
              th(class=['text-center']) Certificado
              th(class=['text-center']) Estado
              th(class=['text-center'], colspan="2") Cuestionarios / Preguntas
              th(class=['text-center'])
          tbody
            tr(v-for="(mc, index) in mcs", :key="index")
              td {{ mcTypes[mc.type] }}
              td {{ mc.name }}
              td
                span(class="badge", :class="mc.active ? 'badge-success' : 'badge-danger'")
                  | {{ mc.active ? 'Habilitado' : 'Deshabilitado' }}
              td(colspan="2")
                router-link(:to="{ name: 'mc-qt-edit', params: { id: mc.id } }", class="btn btn-info btn-sm", style={fontSize: '95%'})
                  span(class="oi oi-plus") 
                  |  Editar
              td
          tfoot
            tr
              td(colspan=3)
              td
                router-link(:to="{ name: 'mc-qt-manage' }", class="btn btn-secondary btn-sm", style={fontSize: '95%'})
                  span(class="oi oi-list") 
                  |  Administrar Cuestionarios
              td
                router-link(:to="{ name: 'mc-qs-manage' }", class="btn btn-secondary btn-sm", style={fontSize: '95%'})
                  span(class="oi oi-list") 
                  |  Administrar Preguntas
              td

</template>

<script>
export default {
  data() {
    return {
      mcs: [],
      mcTypes: {}
    }
  },
  created() {
    this.getMcs();
  },
  methods: {
    getMcs: function () {
      this.eLoading('body', true);

      let url = $('#r-mc-list').prop('value');

      axios.get(url)
        .then(response => {
          this.eLoading('body', false);

          if (response.status === 200) {
            this.mcs     = response.data.mcs;
            this.mcTypes = response.data.mc_types;
          }
        })
        .catch(e => {
          this.eLoading('body', false);
          console.info(e);
        });
    }
  }
}
</script>

<style lang="stylus">

</style>
