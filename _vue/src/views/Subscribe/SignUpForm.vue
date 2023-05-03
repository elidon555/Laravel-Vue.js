<template>
    <form @submit.prevent="">
        <!-- Add a country input field if you live outside of the USA -->
        <v-text-field v-model="billingInfo.name" val="billingInfo.name" label="Name" type="text" />
        <v-text-field v-model="billingInfo.email" label="E-mail" type="email" />
        <v-text-field v-model="billingInfo.address" label="Address" type="text" />
        <v-text-field v-model="billingInfo.city" label="City" type="text"  />
        <div class="grid grid-cols-2 gap-2">
            <v-text-field v-model="billingInfo.state" label="State" type="text" />
            <v-text-field v-model="billingInfo.postal_code" label="Postal Code" type="text"  />
        </div>
        <div class="justify-center m-2">
            <button
                class="w-full mb-2 pb-1 text-white shadow-md bg-indigo-500 border mt-5 rounded-sm hover:bg-indigo-400"
                type="submit"
                :disabled="disabled"
                @click="submit"
            >
                Subscribe
            </button>
        </div>
    </form>
</template>

<script setup>
import {onUpdated, ref} from 'vue'
import FormInput from './FormInput.vue'
import store from "../../store/index.js";
import CustomInput from "../../components/core/CustomInput.vue";

const disabled = ref(false)
const billingInfo = ref({
    email: '',
    name: '',
    address: '',
    city: '',
    state: '',
    postal_code: '',
})

let postBillingInfo = {}

onUpdated(() => {
    postBillingInfo = {
        email: billingInfo.value.email,
        name: billingInfo.value.name,
        shipping: {
            address: {
                city: billingInfo.value.city,
                country: 'US',
                line1: billingInfo.value.address,
                postal_code: billingInfo.value.postal_code,
                state: billingInfo.value.state,
            },
            name: billingInfo.value.name,
        },
        address: {
            city: billingInfo.value.city,
            country: 'US',
            line1: billingInfo.value.address,
            postal_code: billingInfo.value.postal_code,
            state: billingInfo.value.state,
        },
    }

})

function submit(){
    disabled.value = true;
    store.dispatch('createStripleCustomer', postBillingInfo)
        .then(response => {
            console.log(store.state.stripe.clientId)
            if (response.status === 201) {
                // TODO show notification
                store.dispatch('getRoles')
            }
        })
        .catch(err => {
            disabled.value = false;
            // debugger;
        })
}

</script>

