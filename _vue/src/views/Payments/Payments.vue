<template>
  <div class="m-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Payments</h1>
    </div>
    <PaymentsTable />
  </div>

</template>

<script setup>
import {computed} from "vue";
import store from "../../store";
import PaymentsTable from "./PaymentsTable.vue";

const payments = computed(() => store.state.payments);

</script>

<style scoped>

</style>
