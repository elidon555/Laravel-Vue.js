<template>
  <div class="mx-auto">
    <h2 class="text-center text-lg font-bold my-7
      tablet:font-bold tablet:text-2xl">
      <br>
      Choose your Subscription!
    </h2>
    <div class="grid tablet:grid-cols-2 tablet:gap-2 justify-center">

      <v-tabs
          v-model="tab"
          color="deep-purple-accent-4"
          align-tabs="center"
      >
        <v-tab @click="showYearly=false;showMonthly=true" value="monthly">Monthly</v-tab>
        <v-tab  @click="showMonthly=false;showYearly=true"  value="yearly">Yearly</v-tab>
      </v-tabs>
      <v-divider class="m-6"></v-divider>
      <v-window v-model="tab" class="w-full" >
          <v-window-item value="monthly" class="w-full">
            <Transition>
            <div v-if="showMonthly" class="flex">
                <PlanCard
                    title="Basic Plan"
                    :amount="10"
                    icon-1="480p"
                    icon-2="5 GB"
                    duration="month"
                    @click="basicPlan"
                />
                <PlanCard
                    title="Standard Plan"
                    :amount="20"
                    icon-1="720p"
                    icon-2="20 GB"
                    duration="month"
                    @click="standardPlan"
                />
                <PlanCard
                    title="Premium Plan"
                    :amount="50"
                    icon-1="1080p"
                    icon-2="100 GB"
                    duration="month"
                    @click="premiumPlan"
                />
              </div>
            </Transition>
          </v-window-item>
          <v-window-item value="yearly">
            <Transition>
              <div v-if="showYearly" class="flex">
                <PlanCard
                    title="Basic Plan"
                    :amount="99"
                    icon-1="480p"
                    icon-2="5 GB"
                    duration="year"
                    @click="basicPlan"
                />
                <PlanCard
                    title="Standard Plan"
                    :amount="199"
                    icon-1="720p"
                    icon-2="10 GB"
                    duration="year"
                    @click="standardPlan"
                />
                <PlanCard
                    title="Premium Plan"
                    :amount="249"
                    icon-1="1080p"
                    icon-2="100 GB"
                    duration="year"
                    @click="premiumPlan"
                />
              </div>
            </Transition>
          </v-window-item>
      </v-window>



      <!-- Build your card component -->

    </div>
  </div>
</template>

<script setup>

import PlanCard from "./PlanCard.vue";
import store from "../../store";
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {getSubscriptionPlans} from "../../store/actions";

const tab = ref('monthly')
const showMonthly = ref(true)
const showYearly = ref(false)


const route = useRoute()
const userId = computed(() => route.params.id)

async function createPaymentIntent(plan,price) {
  store.dispatch('createPaymentIntent')
      .then(response => {
        store.commit('setPlan', [plan,price])
        store.commit('setStripeClientSecret',[response.data])
      })
      .catch(err => {
        // debugger;
      })
}
const basicPlan = () => {
  const plan = 'BASIC'
  const price = 10
  createPaymentIntent(plan,price)
}

const standardPlan = () => {
  const plan = 'STANDARD'
  const price = 20
  createPaymentIntent(plan,price)
}

const premiumPlan = () => {
  const plan = 'PREMIUM'
  const price = 50
  createPaymentIntent(plan,price)
}

function getSubscriptionPlansData(url = null) {
    store.dispatch("getSubscriptionPlans", {
        url,
        search: '',
        per_page: '',
        sort_field: 'price',
        sort_direction: 'asc',
        id:userId
    }).then(function(response){
    });
}
onMounted(()=>{
    getSubscriptionPlansData()
})

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
