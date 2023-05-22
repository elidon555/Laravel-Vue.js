<template>
    <GuestLayout>
      <form  method="POST" @submit.prevent="signup">
        <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
          Signup
        </h1>
        <v-text-field
            density="compact"
            class="focus:outline-none dark:text-gray-100"
            label="Email"
            v-model="user.name"
        ></v-text-field>

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

        <v-checkbox density="compact" required class="shrink mr-0 mt-0 focus:outline-none dark:text-gray-100" label="I agree to the privacy policy"></v-checkbox>

        <v-btn
            :loading="loading"
            type="submit"
            class="block w-full bg-purple-darken-3  text-sm font-medium
           duration-150 rounded-lg active:bg-purple-600
           hover:bg-purple-800  ">
          Signup
        </v-btn>

        <hr class="my-6 text-white">

        <p class="mt-4">
          <router-link :to="{name: 'login',params:{user_id:userId}}">
            <a class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">
              Already have an account? Login
            </a>
          </router-link>
        </p>
      </form>
    </GuestLayout>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue'
import {LockClosedIcon} from '@heroicons/vue/solid'
import GuestLayout from "../components/GuestLayout.vue";
import store from "../store";
import router from "../router";
import image from "../assets/signup.png";
import {useRoute} from "vue-router";

let loading = ref(false);
let errorMsg = ref("");

const user = ref({
    email: '',
    name: '',
    password: '',
})

const route = useRoute()
const userId = computed(() => route.params.user_id)

async function signup() {
    try {
        loading.value = true;
        await store.dispatch('signup', user.value);
        loading.value = false;
        await router.push({name: 'app.dashboard'});
    } catch ({ response }) {
        loading.value = false;
        errorMsg.value = response.data.message;
    }
}

onMounted(()=>{
  store.dispatch('guestImage',image)
})

</script>
