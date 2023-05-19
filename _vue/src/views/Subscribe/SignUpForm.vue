<template>
  <form @submit.prevent="" class="m-2 max-w-screen-sm m-auto rounded-lg bg-dark shadow-md rounded px-8 pt-6 pb-4 mb-4">
    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Billing details</h5>

    <!-- Add a country input field if you live outside of the USA -->
    <v-text-field v-model="billingInfo.name" val="billingInfo.name" label="Name" type="text" />
    <v-text-field v-model="billingInfo.email" label="E-mail" type="email" />
    <v-text-field v-model="billingInfo.address" label="Address" type="text" />
    <v-text-field v-model="billingInfo.city" label="City" type="text"  />
    <v-text-field v-model="billingInfo.country" label="Country" type="text"  />
    <div class="grid grid-cols-2 gap-2">
      <v-text-field v-model="billingInfo.state" label="State" type="text" />
      <v-text-field v-model="billingInfo.postal_code" label="Postal Code" type="text"  />
    </div>
    <div class="justify-center m-2">
      <v-btn
          color="blue"
          class="w-full mb-4 pb-1 text-white shadow-md bg-indigo-500 border mt-2 rounded-sm hover:bg-indigo-400"
          type="submit"
          :loading="loading"
          @click="submit"
      >
        Update payment method
      </v-btn>
    </div>
  </form>
</template>

<script setup>
import {computed, onMounted, onUpdated, ref, watch} from 'vue'
import store from "../../store/index.js";

const loading = ref(false)

const billingInfo = ref(computed(() => store.state.billingInfo));

let postBillingInfo = {}

onUpdated(() => {
  postBillingInfo = {
    email: billingInfo.value.email,
    name: billingInfo.value.name,
    shipping: {
      address: {
        city: billingInfo.value.city,
        country: billingInfo.value.country,
        line1: billingInfo.value.address,
        postal_code: billingInfo.value.postal_code,
        state: billingInfo.value.state,
      },
      name: billingInfo.value.name,
    },
    address: {
      city: billingInfo.value.city,
      country: billingInfo.value.country,
      line1: billingInfo.value.address,
      postal_code: billingInfo.value.postal_code,
      state: billingInfo.value.state,
    },
  }

})

function submit(){

    loading.value = true;
    store.dispatch('createStripeCustomer', postBillingInfo)
        .then(response => {
          loading.value = false;

        })
        .catch(err => {
          loading.value = false;
            // debugger;
        })
}

function getBillingInfo(){
  store.dispatch('getBillingInfo')
}

onMounted(()=>{
  getBillingInfo()
})

</script>
