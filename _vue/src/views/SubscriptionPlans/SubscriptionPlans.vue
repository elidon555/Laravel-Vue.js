<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Subscription plans</h1>
  </div>
  <SubscriptionPlansTable/>
</template>

<script setup>
import {computed, ref} from "vue";
import store from "../../store";
import SubscriptionPlansTable from "./SubscriptionPlansTable.vue";

const DEFAULT_USER = {
  id: '',
  name: '',
  email: '',
  roles: [],
  permissions: []
}

const users = computed(() => store.state.users);


</script>

<style scoped>

</style>
