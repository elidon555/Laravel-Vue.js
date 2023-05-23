<template>
  <form @submit.prevent="" class="m-2 max-w-sm m-auto rounded-lg bg-dark shadow-md rounded px-8 pt-6 pb-4 mb-4">
    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Billing details</h5>

    <!-- Add a country input field if you live outside of the USA -->
    <v-text-field v-model="billingInfo.name" :rules="rules" val="billingInfo.name" label="Name" type="text" />
    <v-text-field v-model="billingInfo.email" :rules="rules" label="E-mail" type="email" />
    <v-text-field v-model="billingInfo.address" :rules="rules" label="Address" type="text" />
    <v-text-field v-model="billingInfo.city" :rules="rules" label="City" type="text"  />
    <v-text-field v-model="billingInfo.country" :rules="rules" label="Country" type="text"  />
    <div class="grid grid-cols-2 gap-2">
      <v-text-field v-model="billingInfo.state" :rules="rules" label="State" type="text" />
      <v-text-field v-model="billingInfo.postal_code" :rules="rules" label="Postal Code" type="text"  />
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
import {useNotification} from "@kyvg/vue3-notification";

const notification = useNotification()


const loading = ref(false)

const billingInfo = ref(computed(() => store.state.billingInfo));

let postBillingInfo = {}

const rules = [
    value => {
        if (value) return true

        return 'Field is empty.'
    },
];

//Experimental, it's how stripe requires it
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
async function submit() {
    loading.value = true;
    try {
        await store.dispatch('createStripeCustomer', postBillingInfo);
        loading.value = false;
        notification.notify({
            title: "Success!",
            type: "success",
        });
    } catch (err) {
        loading.value = false;
        // debugger;
    }
}

async function getBillingInfo() {
    await store.dispatch('getBillingInfo');
}

onMounted(()=>{
  getBillingInfo()
})

</script>
