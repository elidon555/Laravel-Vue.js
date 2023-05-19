<script setup>

import {ref} from "vue";
import store from "../../store";
import router from "../../router";

const loading = ref(false)
const emit = defineEmits(['paymentIntent'])

const props = defineProps({
  plan:Object
})

async function createPaymentIntent() {
  const plan = props.plan
  if (store.state.user.data.id === undefined) {
    await router.push({name: 'login',params:{user_id:userId.value}})
    return;
  }
  loading.value = true
  store.dispatch('createPaymentIntent',plan)
      .then(response => {
      })
      .catch(err => {
      })
      .finally(()=>{
        loading.value = false;
      })
}

</script>

<template>
  <div  class="p-3 m-auto">
    <v-card class="max-w-md m-auto lg:m-0 ">
      <div class="m-1 bg-gradient-to-tl from-gray-700 via-gray-900 to-black">
        <div class="text-center text-lg font-bold pt-3">
          {{ plan.title }}
        </div>
        <br>
        <v-img
            class="mx-8 rounded-lg"
            :src="'https://picsum.photos/500/300?image='+Math.floor(Math.random() * 100)"
            cover
        ></v-img>

        <div class="text-center text-2xl font-semibold my-4">
          $1{{parseInt(plan.price)}} / {{plan.duration}}
        </div>
        <v-card-actions>
          <v-btn
              class="w-full"
              color="blue"
              variant="elevated"
              :loading="loading"
              @click="createPaymentIntent"
          >
            Join
          </v-btn>

        </v-card-actions>

        <v-card-text v-html="plan.description"/>

      </div>

    </v-card>
  </div>

</template>
