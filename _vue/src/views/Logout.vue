<template>
  <loading v-model:active="isLoading"
           :can-cancel="true"
           :is-full-page="true"/>
</template>

<script setup>

import {onMounted, ref} from "vue";
import router from "../router";
import store from "../store";
import Loading from 'vue-loading-overlay';

const isLoading = ref(true)

onMounted(()=>{
  store.dispatch('logout')
    .then(() => {
      router.push({name: 'login'})
    })

})
</script>

<style scoped>

</style>