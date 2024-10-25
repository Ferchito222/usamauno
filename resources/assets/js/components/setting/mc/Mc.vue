<template lang="pug">
  div(class=['row'], style={ fontSize: '70%;' })
    div(class=['col'])
      router-view
      notifications(group="mc")

</template>

<script>
import Vue from 'vue';
import VueRouter from 'vue-router';

import McList from './McList.vue';
import QuestionnaireEdit from './QuestionnaireEdit.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/list',
    name: 'mc-list',
    component: McList,
    props: true
  },
  {
    path: '/questionnaire/:id/edit',
    name: 'mc-qt-edit',
    component: QuestionnaireEdit
  },
  {
    path: '/questionnaire/manage',
    name: 'mc-qt-manage',
    component: McList
  },
  {
    path: '/questions/manage',
    name: 'mc-qs-manage',
    component: McList
  }
];

const router = new VueRouter({
  routes,
});

export default {
  router: router,
  created() {
    if (this.$route.name == null) {
      this.$router.push({ name: 'mc-list' });
    }
  },
}
</script>

<style lang="stylus">

</style>
