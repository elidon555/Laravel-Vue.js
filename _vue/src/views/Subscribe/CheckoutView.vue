<script setup>
import SignUpForm from './SignUpForm.vue'
import CheckoutForm from './CheckoutForm.vue'
import store from "../../store";
import PlanView from "./PlanView.vue";

</script>

<template>

  <div class="border-slate-600 rounded-sm mx-auto mt-10 w-72 shadow-lg tablet:w-1/2 bg-dark rounded-lg"
      :class="[store.state.plan.name === '' && store.state.stripe.clientId !==''  ? 'w-75':'w-25']">
    <div v-show="store.state.plan.name !== '' || store.state.stripe.clientId ===''">
      <h5  class="bg-slate-300 h-10 text-center font-semibold py-2 bg-dark">Subscribe to our Newsletter</h5>
      <p class="text-center mb-2">* Cancel anytime</p>
    </div>


      <div v-if="store.state.stripe.clientId ===''">
        {{store.state.plan.name}}
          <SignUpForm />
      </div>
      <div v-else-if="store.state.plan.name ===''">
        <PlanView />
      </div>
      <div v-else>
        <CheckoutForm />
      </div>
  </div>
</template>
