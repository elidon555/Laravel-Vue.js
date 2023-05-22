<template>
  <div v-if="subscriptionPlans.yearly.length || subscriptionPlans.monthly.length" class="mx-auto">
    <h1 class="text-center text-2xl font-bold my-4 tracking-wider">
      <br>
      Select a membership level
    </h1>
    <div class="justify-center max-w-6xl m-auto">
      <v-tabs v-model="tab" color="deep-purple-accent-4" align-tabs="center">
        <v-tab v-if="subscriptionPlans.monthly.length" value="monthly">Monthly</v-tab>
        <v-tab v-if="subscriptionPlans.yearly.length" value="yearly">Yearly</v-tab>
      </v-tabs>
      <v-divider class="m-3"></v-divider>
      <v-window v-model="tab" class="w-full">
        <v-window-item :value="tab">
          <Transition>
            <div class="m-2">
              <div class="mx-4 flex flex-row flex-wrap">
                <PlanCard
                    v-for="plan in subscriptionPlans[tab]"
                    class="basis-full md:basis-1/3"
                    :plan="plan"
                    :userId="userId"
                />
              </div>
            </div>
          </Transition>
        </v-window-item>
      </v-window>
    </div>
  </div>
  <br><br>
  <v-divider class="border-opacity-25"></v-divider>
</template>

<script setup>

import PlanCard from "./PlanCard.vue";
import store from "../../store";
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import router from "../../router";

const tab = ref('monthly')

const route = useRoute()
const userId = computed(() => route.params.id)
const subscriptionPlans = computed(()=>store.state.subscriptionPlans)

async function getSubscriptionPlansData(url = null) {
    try {
        await store.dispatch("getSubscriptionPlans", {
            url,
            search: '',
            per_page: '',
            sort_field: 'price',
            sort_direction: 'asc',
            id: userId.value
        });
        // Rest of the code after successful response
    } catch (error) {
        // Error handling code
    }
}
onMounted(()=>{
  getSubscriptionPlansData()
})

</script>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 1s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
