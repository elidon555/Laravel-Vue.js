<template>
  <div class="m-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Subscription plans</h1>
      <button type="button"
              @click="showAddNewModal"
              class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add your new subscription Plan
      </button>
    </div>
    <SubscriptionPlansTable @clickEdit="editSubscriptionPlan"/>
    <SubscriptionPlanModal v-model="showUserModal" :subscriptionPlan="subscriptionPlanModel"  @close="onModalClose"/>
  </div>

</template>

<script setup>
import {computed, ref} from "vue";
import store from "../../store";
import SubscriptionPlansTable from "./SubscriptionPlansTable.vue";
import SubscriptionPlanModal from "./SubscriptionPlanModal.vue";

const DEFAULT_SUBSCRIPTION_PLAN = {
  id: '',
  name: '',
  price: '',
  interval: '',
  features:''
}

const subscriptionPlanModel = ref({...DEFAULT_SUBSCRIPTION_PLAN})
const showUserModal = ref(false);

function showAddNewModal() {
  showUserModal.value = true
}

function editSubscriptionPlan(u) {
  subscriptionPlanModel.value = u;
  showAddNewModal();
}

function onModalClose() {
    console.log('ok')
  subscriptionPlanModel.value = {...DEFAULT_SUBSCRIPTION_PLAN}
}


</script>

<style scoped>

</style>
