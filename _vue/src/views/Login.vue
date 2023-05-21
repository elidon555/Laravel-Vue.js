<template>
  <GuestLayout>
    <v-form ref="form" fast-fail  method="POST" @submit.prevent="login">
      <h1 class="mb-4 text-xl font-semibold text-gray-200">
        Login
      </h1>
      <v-text-field
          density="compact"
          class="focus:outline-none text-gray-100 mb-2"
          label="Email"
          v-model="user.email"
          :rules="rules"
      ></v-text-field>

      <v-text-field
          density="compact"
          :type="'password'"
          class="focus:outline-none text-gray-100 mb-2"
          label="Password"
          placeholder="Enter your password"
          v-model="user.password"
          :rules="rules"
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
          <a class="text-sm font-medium text-purple-600 text-purple-400 hover:underline">
            Forgot your password?
          </a>
        </router-link>
      </p>
      <p class="mt-1">
        <router-link :to="{name: 'signup',params:{user_id:userId}}">
          <a class="text-sm font-medium text-purple-600 text-purple-400 hover:underline">
            Create account
          </a>
        </router-link>

      </p>
    </v-form>

  </GuestLayout>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue'
import GuestLayout from "../components/GuestLayout.vue";
import store from "../store";
import router from "../router";
import image from '../assets/login.png'
import {useRoute} from "vue-router";
import {useNotification} from "@kyvg/vue3-notification";
import Swal from 'sweetalert2'

let loading = ref(false);
let errorMsg = ref("");

const route = useRoute()
const userId = computed(() => route.params.user_id)
const authUser = computed(() => store.state.user.data);
const notify = useNotification()

const user = ref({
  email: '',
  password: '',
  remember: false
})

const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];

async function login() {
  const {valid} = await form.value.validate();

  if (!valid) return

  loading.value = true;
  store.dispatch('login', user.value)
      .then(() => {
        redirect()
      })
      .catch(() => {
        Swal.fire({
          title: 'Error!',
          icon: 'error',
        })

        loading.value = false;
      })
}

function redirect(){
  const userRoles = store.state.user.data.roles.map(role=>role['name'])
  let route = 'app.dashboard';

  if (userRoles.includes('user')) route = 'app.contents'
  router.push({name:route,params:{id:userId.value || authUser.value.id || ''}})
}

onMounted(()=>{
  store.dispatch('guestImage',image)
})

</script>
