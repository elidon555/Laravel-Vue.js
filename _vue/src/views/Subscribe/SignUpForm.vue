<script setup>
import { ref } from 'vue'
import FormInput from './FormInput.vue'

const name = ref('')
const email = ref('')
const address = ref('')
const city = ref('')
const postal_code = ref('')
const state = ref('')
const disabled = ref(false)


</script>

<template>
  <form @submit.prevent="">
    <!-- Add a country input field if you live outside of the USA -->
    <FormInput v-model="name" name="Name" type="text" />
    <FormInput v-model="email" name="E-mail" type="email" />
    <FormInput v-model="address" name="Address" type="text" />
    <FormInput v-model="city" name="City" type="text"  />
    <div class="grid grid-cols-2 gap-2">
      <FormInput v-model="state" name="State" type="text" />
      <FormInput v-model="postal_code" name="Postal Code" type="text"  />
    </div>
    <div class="justify-center m-2">
      <button
        class="w-full mb-2 pb-1 text-white shadow-md bg-indigo-500 border mt-5 rounded-sm hover:bg-indigo-400"
        type="submit"
        :disabled="disabled"
      >
        Subscribe
      </button>
    </div>
  </form>
</template>
