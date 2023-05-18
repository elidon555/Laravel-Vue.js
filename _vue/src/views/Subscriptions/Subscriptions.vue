<template>
  <div class="m-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Subscriptions</h1>
      <button type="button"
              @click="showAddNewModal"
              class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add new Subscription
      </button>
    </div>
    <SubscriptionsTable @clickEdit="editSubscription"/>
<!--    <SubscriptionModal v-model="showSubscriptionModal" :subscription="subscriptionModel" @close="onModalClose"/>-->
  </div>

</template>

<script setup>
import {computed, ref} from "vue";
import store from "../../store";
// import SubscriptionModal from "./SubscriptionModal.vue";
import SubscriptionsTable from "./SubscriptionsTable.vue";

const DEFAULT_USER = {
  id: '',
  name: '',
  email: '',
  roles: [],
  permissions: []
}

const subscriptions = computed(() => store.state.subscriptions);

const subscriptionModel = ref({...DEFAULT_USER})
const showSubscriptionModal = ref(false);

function showAddNewModal() {
  showSubscriptionModal.value = true
}

function editSubscription(u) {
    subscriptionModel.value = u;
    showAddNewModal();
}

function onModalClose() {
  subscriptionModel.value = {...DEFAULT_USER}
}

</script>

<style scoped>

</style>
