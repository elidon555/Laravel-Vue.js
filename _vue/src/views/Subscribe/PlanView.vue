<template>
  <div class="mx-auto">
    <h2 class="text-center text-lg font-bold my-7
      tablet:font-bold tablet:text-2xl">
      Choose your Subscription!
    </h2>
    <div class="grid tablet:grid-cols-2 tablet:gap-2 justify-center">
      <!-- Build your card component -->
      <PlanCard
          title="Standard Plan"
          :amount="1"
          icon-1="2 team members"
          icon-2="5gb"
          @click="basicPlan"
      />
      <PlanCard
          title="Standard Plan"
          :amount="10"
          icon-1="4 team members"
          icon-2="10gb"
          @click="standardPlan"
      />
      <PlanCard
          title="Premium Plan"
          :amount="50"
          icon-1="4 team members"
          icon-2="10gb"
          @click="premiumPlan"
      />
    </div>
  </div>
</template>

<script setup>

import PlanCard from "./PlanCard.vue";
import store from "../../store";

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
  const price = 1
  createPaymentIntent(plan,price)
}

const standardPlan = () => {
  const plan = 'STANDARD'
  const price = 10
  createPaymentIntent(plan,price)
}

const premiumPlan = () => {
  const plan = 'PREMIUM'
  const price = 50
  createPaymentIntent(plan,price)
}

</script>
