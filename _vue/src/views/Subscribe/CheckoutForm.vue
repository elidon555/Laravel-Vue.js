<template>
    <!--  <div v-if="planStore.planData.clientSecret">-->
    <div class="max-w-sm mx-auto border-lg rounded-lg mt-8">
        <ul class="my-7 mx-3 space-y-2" role="list">
            <li class="flex space-x-3">
                <!-- Icon -->
                <svg class="w-8 h-8 text-gray-600" viewBox="0 0 24 24">
                    <path d="M4,15V9H12V4.16L19.84,12L12,19.84V15H4Z" fill="currentColor"/>
                </svg>
                <span class="text-2xl font-normal leading-tight text-gray-300"
                >Total: <b>${{ store.state.plan.price }}</b></span
                >
            </li>
            <li class="flex space-x-3">
                <svg class="w-8 h-8 text-gray-600" viewBox="0 0 24 24">
                    <path d="M4,15V9H12V4.16L19.84,12L12,19.84V15H4Z" fill="currentColor"/>
                </svg>
                <span class="text-2xl font-normal leading-tight text-gray-300"
                >Plan: <b>{{ store.state.plan.name }}</b></span
                >
            </li>
            <li class="flex space-x-3">
                <svg class="w-8 h-8 text-gray-600" viewBox="0 0 24 24">
                    <path d="M4,15V9H12V4.16L19.84,12L12,19.84V15H4Z" fill="currentColor"/>
                </svg>
                <span class="text-2xl font-normal leading-tight text-gray-300"
                >Name: <b>{{ store.state.stripe.clientName }}</b></span
                >
            </li>
        </ul>
<!--        <div class="mt-5">-->
<!--          <StripeElements></StripeElements>-->
<!--        </div>-->
        <div class="mt-5">
<!--            <StripeElements></StripeElements>-->
            <!-- stripe -->
            <div ref="card" class="mx-3 p-2.5 rounded-md border-2 border-solid border-gray-500 bg-dark">
                <!-- Elements will create input elements here -->
            </div>

            <!-- We'll put the error messages in this element -->
            <div
                id="card-errors"
                class="mx-3 text-error-message text-lg font-semibold"
                role="alert"
            ></div>
            <div class="justify-center mx-3">
                <v-btn
                    color="blue"
                    :loading="disabled"
                    class="w-full h-8 mb-3 text-white shadow-md bg-indigo-500 border mt-5 rounded-md hover:bg-indigo-400 pb-1"
                    @click="Submit"
                >
                    Pay with Stripe
                </v-btn>
            </div>
        </div>
    </div>
</template>


<script setup>
import {ref, onMounted,computed} from 'vue'
import store from "../../store";
import router from "../../router";
import StripeElements from "../../components/StripeElements.vue";
import * as notification from "@kyvg/vue3-notification";
import Swal from "sweetalert2"

const contentUser = computed(() => store.state.contents.user);

const disabled = ref(false)
const card = ref(null)

const emit = defineEmits(['complete']);

const stripe = window.Stripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY)
const key = import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY;
const elements = stripe.elements()
const style = {
  base: {
    color: "#CFD7DF",
    fontWeight: 500,
    fontFamily: "Inter, Open Sans, Segoe UI, sans-serif",
    fontSize: "18px",
    fontSmoothing: "antialiased",

    "::placeholder": {
      color: "#CFD7DF"
    }
  },
  invalid: {
    color: "#E25950"
  }
}

const el = elements.create('card', {style: style})

function displayError(event) {
  const displayError = document.getElementById('card-errors')
  if (event.error) {
    displayError.textContent = event.error.message
  } else {
    displayError.textContent = ''
  }
}

onMounted(() => {
  el.mount(card.value)

  el.on('change', (event) => {
    displayError(event)
  })
})

const Submit = async () => {
  disabled.value = true
  const clientSecret = store.state.stripe.clientSecret
  const fullName = store.state.stripe.clientName

  const result = await stripe.confirmCardSetup(clientSecret, {
    payment_method: {
      type: 'card',
      card: el,
      billing_details: {
        name: fullName
      }
    }
  })
  if (result.error) {
    disabled.value = false
    alert(result.error.message)
  } else {
    let data = {
      name: store.state.plan.name,
      paymentMethodId: result.setupIntent.payment_method,
      priceId: store.state.plan.price_id,
    }
    store.dispatch('createStripeSubscription', data)
        .then(response => {
          Swal.fire({
            title: 'Success!',
            text:'You are successfully subscribed to '+contentUser.value.name,
            icon: 'success',
          })
            store.dispatch("getCurrentUser")
            emit('complete')
        })
        .catch(err => {
          // debugger;
        })
  }
}

</script>
