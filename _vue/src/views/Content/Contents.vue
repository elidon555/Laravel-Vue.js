<template>
    <ProfileHeader :edit ="false" :images="contents.images" :key="$route.path"/>
    <br><br><br>

    <div v-if="!user.token">
        <PlanView v-if="stripe.clientSecret ===''"></PlanView>
    </div>
    <div v-else>
      <div v-if="planName!==''" class="flex justify-content-center mt-8">
        <v-btn color="blue" variant="tonal">Plan: {{planName}}</v-btn>
      </div>
      <div v-else-if=" user.data.id !== parseInt(userId) && planName==='' && contents.subscriptionPlans.length " class="mt-6">
          <PlanView v-if="stripe.clientSecret === ''"/>
          <BillingDetails v-else-if="stripe.clientSecret !=='' && stripe.clientId ===''"/>
          <CheckoutForm @complete = "getContents()" v-else-if="stripe.clientId !==''" />
      </div>
    </div>
  <Transition>
    <div v-show="!contents.loading">
      <div v-if="!contents.loading">
        <h1 class="text-3xl font-semibold text-center mt-3"><br>Recent posts by {{contents.user.name}}</h1>
      </div>
    </div>
  </Transition>

  <ContentsTable ref='contentsTable' v-show="!contents.loading" />
  <ContentModal v-model="showContentModal" :content="contentModel" @close="onModalClose"></ContentModal>
  <button v-if="user.token != null && user.data.id === parseInt(userId)" type="button"
          @click="showAddNewModal"
          class="animate-pulse fixed bottom-10 right-8 w-15 h-15 font-semibold   p-0 w-16 h-16 bg-indigo-600 rounded-full hover:bg-indigo-700 active:shadow-lg mouse shadow transition ease-in duration-200
          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
  ><v-icon icon="mdi-plus" size="x-large"></v-icon></button>

</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import store from "../../store";
import ContentsTable from "./ContentsTable.vue";
import ContentModal from "./ContentModal.vue";
import PlanView from "../Subscribe/PlanView.vue";
import CheckoutForm from "../Subscribe/CheckoutForm.vue";
import {useRoute} from "vue-router";
import BillingDetails from "../Subscribe/BillingDetails.vue";
import ProfileHeader from "../Profile/ProfileHeader.vue";

const contents = computed(() => store.state.contents);
const user = computed(() => store.state.user);
const stripe = computed(() => store.state.stripe);
const planName = computed(() => getPlanName());

const showContentModal = ref(false);
const contentsTable = ref(null);

const route = useRoute()
const userId = computed(() => route.params.id || user.value.data.id)

const DEFAULT_CONTENT = {
  id: '',
  title: '',
  description:'',
  isPublic : 0,
}

const contentModel = ref({...DEFAULT_CONTENT})

function showAddNewModal() {
    showContentModal.value = true
}

function getContents() {
  contentsTable.value.getContents()
}

function editContent(u) {
  contentModel.value = u;
  showAddNewModal();
}

function getPlanName(){

  if (!user.value.data.subscriptions.length) {
    return '';
  }
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
.v-enter-active,
.v-leave-active {
  transition: opacity 1s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
