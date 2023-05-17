<template>
    <div v-if="subscriptionPlans.yearly.length || subscriptionPlans.monthly.length" class="mx-auto">
        <h1 class="text-center text-2xl font-bold my-4 tracking-wider">
            <br>
            Select a membership level
        </h1>
        <div class="justify-center max-w-6xl m-auto">

            <v-tabs
                v-model="tab"
                color="deep-purple-accent-4"
                align-tabs="center"
            >
                <v-tab v-if="subscriptionPlans.monthly.length" @click="showYearly=false;showMonthly=true" value="monthly">Monthly</v-tab>
                <v-tab  v-if="subscriptionPlans.yearly.length" @click="showMonthly=false;showYearly=true"  value="yearly">Yearly</v-tab>
            </v-tabs>
            <v-divider class="m-3"></v-divider>
            <v-window v-model="tab" class="w-full" >
                <v-window-item value="monthly">
                    <Transition>
                        <div v-if="showMonthly" class="m-2">
                            <v-row class="mx-4">
                                <v-col cols="12" sm="12" md="4" lg="4" v-for="plan in subscriptionPlans.monthly">

                                    <PlanCard
                                        :title="plan.name"
                                        :amount="parseInt(plan.price)"
                                        :icon-1="plan.features.split(' | ')[0]"
                                        :icon-2="plan.features.split(' | ')[1]"
                                        :duration="plan.interval"
                                    />
                                </v-col>

                            </v-row>
                        </div>
                    </Transition>
                </v-window-item>
                <v-window-item value="yearly">
                    <Transition>
                        <div v-if="showYearly" class="flex">
                            <div v-for="plan in subscriptionPlans.yearly">
                                <PlanCard
                                    :title="plan.name"
                                    :amount="parseInt(plan.price)"
                                    :icon-1="plan.features.split(' | ')[0]"
                                    :icon-2="plan.features.split(' | ')[1]"
                                    :duration="plan.interval"
                                    @click="createPaymentIntent(plan)"
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
import {getSubscriptionPlans} from "../../store/actions";
import router from "../../router";

const tab = ref('monthly')
const showMonthly = ref(true)
const showYearly = ref(false)


const route = useRoute()
const userId = computed(() => route.params.id)
const subscriptionPlans = computed(()=>store.state.subscriptionPlans)

async function createPaymentIntent(plan) {
    if (store.state.user.data.id === undefined) {
        await router.push({name: 'login',params:{user_id:userId.value}})
        return;
    }

    store.dispatch('createPaymentIntent')
        .then(response => {
            store.commit('setPlan', [plan.name,plan.price,plan.price_id])
            store.commit('setStripeClientSecret',[response.data])
        })
        .catch(err => {
            // debugger;
        })
}

function getSubscriptionPlansData(url = null) {
    store.dispatch("getSubscriptionPlans", {
        url,
        search: '',
        per_page: '',
        sort_field: 'price',
        sort_direction: 'asc',
        id:userId.value
    }).then(function(response){
    });
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
