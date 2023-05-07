<template>

  <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
    <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
      <div class="flex flex-col overflow-y-auto md:flex-row bg-dark">
          <div class="h-32 md:h-auto md:w-1/2">
            <img aria-hidden="true" class="object-cover w-full h-full dark:block" :src="store.state.guestImage" alt="Office">
          </div>
          <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
              <slot></slot>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script setup>

import {ref} from "vue";
import store from "../store";


const propValue = ref()

const {title} = defineProps({
  title: String
})
</script>

<style scoped>

</style>
