<template>
  <div class="mx-8 mt-4">
    <div class="grid gap-3 grid-cols-3">
      <div class="col-span-2 grid grid-cols-2 gap-3">
        <router-link :to="{name: 'reports.subscriptions', params: route.params}"
                     class=" text-light rounded-md text-center"
                     active-class="text-light"><v-btn class="w-100 mt-2">Subscriptions Report</v-btn>
        </router-link>
        <router-link :to="{name: 'reports.subscribers', params: route.params}"
                     class="text-light rounded-md text-center"
                     active-class="text-light"><v-btn class="w-100 mt-2">Subscriber Report</v-btn>
        </router-link>
      </div>
      <div >
        <v-select  :items="dateOptions"  v-model="chosenDate" label="Date" @update:modelValue="onDatePickerChange"></v-select>
      </div>
    </div>
    <div class="bg-dark p-3 rounded-md mt-3 shadow-md">
      <router-view />
    </div>
  </div>
</template>

<script setup>
import {computed, ref} from "vue";
import CustomInput from "../../components/core/CustomInput.vue";
import {useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";

const store = useStore()
const router = useRouter();
const route = useRoute();
const dateOptions = computed(() => store.state.dateOptions);
const chosenDate = ref('all')

function onDatePickerChange() {
  router.push({name: route.name, params: {date: chosenDate.value}})
}
</script>

<style scoped>

</style>
