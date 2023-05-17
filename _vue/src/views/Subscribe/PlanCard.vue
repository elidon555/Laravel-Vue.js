<script setup>

import {ref} from "vue";

const loading = ref(false)
const show = ref(false)

const props = defineProps({
  title:String,
  amount: Number,
  description: String,
  duration:String
})
</script>

<template>
  <div class="p-3 ">
    <v-card class="max-w-md m-auto lg:m-0 ">
      <div class="m-1 bg-gradient-to-tl from-gray-700 via-gray-900 to-black">
        <div class="text-center text-lg font-bold pt-3">
          {{ title }}
        </div>
        <br>
        <v-img
            class="mx-8 rounded-lg"
            :src="'https://picsum.photos/500/300?image='+Math.floor(Math.random() * 100)"
            cover
        ></v-img>

        <div class="text-center text-2xl font-semibold my-4">
          $1{{amount}} / {{duration}}
        </div>
        <v-card-actions>
          <v-btn
              class="w-full"
              color="blue"
              variant="elevated"
          >
            Join
          </v-btn>

        </v-card-actions>

        <v-card-text v-html="description"/>

      </div>

    </v-card>
  </div>

</template>
