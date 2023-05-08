<template>
  <GuestLayout>
    <form  method="POST">
      <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Forgot password
      </h1>
      <v-text-field
          density="compact"
          class="focus:outline-none dark:text-gray-100"
          label="Email"
      ></v-text-field>

      <v-btn
          type="submit"
          class="block w-full bg-purple-darken-3  text-sm font-medium
           duration-150 rounded-lg active:bg-purple-600
           hover:bg-purple-800  ">
        Recover password
      </v-btn>

    </form>
  </GuestLayout>
</template>

<script setup>
import {LockClosedIcon} from '@heroicons/vue/solid'
import GuestLayout from "../components/GuestLayout.vue";
import {onMounted} from "vue";
import store from "../store";
import image from "../assets/forgot-password.png";

onMounted(()=>{
  store.dispatch('guestImage',image)
})
</script>
