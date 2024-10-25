<template lang="pug">
  div
    div(class=['row'], style={backgroundColor: '#fff'})
      div(class=['col-sm-2'])
      div(class=['col-sm-8', 'text-center'], style={paddingTop: '3px'})
        div(v-for="(question, index) in questions",
          :key="index",
          class=['row'],
          style={marginBottom: '3px'}
        )
          div(class=['col-sm-2'])
            toggle-button(
              color="#54d378",
              :sync="true",
              class="changed-font"
            )
          div(class=['col-sm-10', 'text-left']) {{ question.question }}
      div(class=['col-sm-2'])
</template>

<script>
export default {
  data: () => {
    return {

    }
  },
  props: {
    questions: {
      type: Array,
      default: []
    }
  },
  created() {
    console.log(this.questions)
  }
}
</script>

<style lang="stylus">

</style>
