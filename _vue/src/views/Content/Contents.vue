<template>
    <div v-if="getPlanName()" class="d-flex justify-content-center">
        <v-btn class="m-auto" color="blue">Plan: {{getPlanName()}}</v-btn>
    </div>
    <div v-else-if="store.state.user.data.id !== parseInt(userId) && getPlanName()=='' && (subscriptionPlansCheck.monthly.length ||subscriptionPlansCheck.yearly.length) ">
        <div v-if="subscriptionPlans.map((item)=>item.price_id)"></div>
        <PlanView v-if="store.state.stripe.clientSecret ===''"></PlanView>
        <CheckoutForm v-if="store.state.stripe.clientSecret !==''" />
    </div>

  <div class="flex items-center justify-between mb-3">


    <h1 class="text-3xl font-semibold">Contents</h1>
    <button v-if="store.state.user.data.id === parseInt(userId)" type="button"
            @click="showAddNewModal"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new Content
    </button>
  </div>

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
const contentSubscriptionPlans = computed(() => store.state.contents.subscriptionPlans);
const subscriptionPlans = computed(() => store.state.subscriptionPlans);
const subscriptionPlansCheck =store.state.subscriptionPlans;

const showContentModal = ref(false);

const route = useRoute()
const userId = computed(() => route.params.id)

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
    const userSubscriptions = store.state.user.data.subscriptions;
    const subscriptionPlans =store.state.contents.subscriptionPlans;

    let userPlanName = '';

    for (const subscription of userSubscriptions) {
        const plan = subscriptionPlans.find(plan => plan.price_id === subscription.stripe_price);
        if (plan) {
            return userPlanName = plan.name;
        }
    }
    return '';
}

function onModalClose() {
    contentModel.value = {...DEFAULT_CONTENT}
}

</script>

<style scoped>

</style>
