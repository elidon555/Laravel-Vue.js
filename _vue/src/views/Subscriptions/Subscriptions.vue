<template>
  <div class="m-8">
    <SubscriptionsTable @clickEdit="editSubscription"/>
    <SubscriptionModal v-model="showSubscriptionModal" :subscription="subscriptionModel" @close="onModalClose"/>
  </div>

</template>

<script setup>
import {computed, ref} from "vue";
import store from "../../store";
import SubscriptionsTable from "./SubscriptionsTable.vue";
import SubscriptionModal from "./SubscriptionModal.vue";

const DEFAULT_SUBSCRIPTION = {
  id: '',
  userId: '',
  contentCreator:'',
  subscriptionPlan: '',
}

const subscriptions = computed(() => store.state.subscriptions);

const subscriptionModel = ref({...DEFAULT_SUBSCRIPTION})
const showSubscriptionModal = ref(false);

function showAddNewModal() {
  showSubscriptionModal.value = true
}

function editSubscription(u) {
    subscriptionModel.value = {
      id: u.id,
      userId: u.user.id,
      contentCreator: {value:u.subscribed_user.id,title:u.subscribed_user.name},
      subscriptionPlan: {value:u.plan.id,title:u.plan.name},
    };

    showAddNewModal();
}

function onModalClose() {
  subscriptionModel.value = {...DEFAULT_SUBSCRIPTION}
}

</script>

<style scoped>

</style>
