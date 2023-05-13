<template>
    <div v-if="!user.token">
        <PlanView v-if="store.state.stripe.clientSecret ===''"></PlanView>
    </div>
    <div v-else>
      <div v-if="getPlanName()" class="d-flex justify-content-center">
        <v-btn class="m-auto" color="blue">Plan: {{getPlanName()}}</v-btn>
      </div>
      <div v-else-if=" user.data.id !== parseInt(userId) && getPlanName()=='' && contents.subscriptionPlans.length ">
        <PlanView v-if="store.state.stripe.clientSecret ===''"></PlanView>
        <CheckoutForm v-if="store.state.stripe.clientSecret !==''" />
      </div>
    </div>
  <div class="flex items-center justify-between ">
    <span></span>
    <button v-if="user.token != null && user.data.id === parseInt(userId)" type="button"
            @click="showAddNewModal"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new Content
    </button>
  </div>
  <h1 class="text-3xl font-semibold text-center mt-3"><br>Recent posts</h1>


  <ContentsTable/>
  <ContentModal v-model="showContentModal" :content="contentModel" @close="onModalClose"></ContentModal>


</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import ContentsTable from "./ContentsTable.vue";
import ContentModal from "./ContentModal.vue";
import PlanView from "../Subscribe/PlanView.vue";
import CheckoutForm from "../Subscribe/CheckoutForm.vue";
import {useRoute} from "vue-router";

const contents = computed(() => store.state.contents);
const user = computed(() => store.state.user);

const showContentModal = ref(false);

const route = useRoute()
const userId = computed(() => route.params.id ? route.params.id : user.value.data.id)

const DEFAULT_CONTENT = {
  id: '',
  title: '',
  description:''
}

const contentModel = ref({...DEFAULT_CONTENT})

function showAddNewModal() {
    showContentModal.value = true
}

function editContent(u) {
  contentModel.value = u;
  showAddNewModal();
}

function getPlanName(){

  const userSubscription = user.value.data.subscriptions.find(subscription =>
      contents.value.subscriptionPlans.some(plan => plan.price_id === subscription.stripe_price)
  );

  return userSubscription ? contents.value.subscriptionPlans.find(plan => plan.price_id === userSubscription.stripe_price).name : '';
}

function onModalClose() {
    contentModel.value = {...DEFAULT_CONTENT}
}

</script>

<style scoped>

</style>
