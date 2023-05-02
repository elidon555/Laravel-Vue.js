<script setup >
import { Field } from 'vee-validate'
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  modelValue: String,
  name: String,
  type: String,
  // rules: String
})

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div class="relative z-0 mb-6 mx-4 group">
      <input
          :class="[
          'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer '
        ]"
          v-bind="modelValue"
          :type="type"
          :value="modelValue"
          placeholder=" "
          required
      />
      <label
          class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-8 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-8"
          for="name"
      >{{ name }}</label
      >
  </div>
</template>
