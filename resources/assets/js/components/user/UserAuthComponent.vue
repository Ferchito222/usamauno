<template lang="pug">

  div
    password(:user="user", :reset="false", :minChar="7", :maxChar="14")

    notifications(group="user")

</template>

<script>
import Password from './PasswordComponent.vue'

export default {
  props: {
    user: {
      type: Object,
    },
  },
  components: {
    Password,
  },
  created() {
    
  },
  methods: {
    
  }

}
</script>