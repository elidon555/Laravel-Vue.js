<template>
  <GuestLayout>
    <form  method="POST" @submit.prevent="login">
      <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Login
      </h1>
      <v-text-field
          density="compact"
          class="focus:outline-none dark:text-gray-100"
          label="Email"
          v-model="user.email"
      ></v-text-field>

      <v-text-field
          density="compact"
          :type="'password'"
          class="focus:outline-none dark:text-gray-100"
          label="Password"
          placeholder="Enter your password"
          v-model="user.password"
      ></v-text-field>

      <v-btn
          :loading="loading"
          type="submit"
          class="block w-full bg-purple-darken-3  text-sm font-medium
           duration-150 rounded-lg active:bg-purple-600
           hover:bg-purple-800  ">
        Log in
      </v-btn>

      <hr class="my-6 text-white">

      <p class="mt-4">
        <router-link :to="{name: 'requestPassword'}">
          <a class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">
            Forgot your password?
          </a>
        </router-link>
      </p>
      <p class="mt-1">
        <router-link to="/signup">
          <a class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">
            Create account
          </a>
        </router-link>

      </p>
    </form>

  </GuestLayout>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import {LockClosedIcon} from '@heroicons/vue/solid'
import GuestLayout from "../components/GuestLayout.vue";
import store from "../store";
import router from "../router";
import {setStripeClientSecret} from "../store/mutations";
import image from '../assets/login.png'

let loading = ref(false);
let errorMsg = ref("");

const user = ref({
  email: '',
  password: '',
  remember: false
})

function login() {
  loading.value = true;
  store.dispatch('login', user.value)
      .then(() => {
        loading.value = false;
        redirect()
      })
      .catch(({response}) => {
        loading.value = false;
        errorMsg.value = response.data.message;
      })
}

function redirect(){
  const userRoles = store.state.user.data.roles.map(role=>role['name'])
  let route = 'app.dashboard';
  if (userRoles.includes('user')) route = 'app.contents'
  router.push({name:route})
}

onMounted(()=>{
  store.dispatch('guestImage',image)
})

</script>
