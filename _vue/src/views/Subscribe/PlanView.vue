<template>
    <div class="mx-auto">
        <h2 class="text-center text-lg font-bold my-7
      tablet:font-bold tablet:text-2xl">
            Choose your Subscription!
        </h2>
        <div class="grid tablet:grid-cols-2 tablet:gap-2">
            <!-- Build your card component -->
            <PlanCard
                title="Standard Plan"
                :amount="1"
                icon-1="2 team members"
                icon-2="5gb"
                @click="basicPlan"
            />
            <PlanCard
                title="Standard Plan"
                :amount="10"
                icon-1="4 team members"
                icon-2="10gb"
                @click="standardPlan"
            />
            <PlanCard
                title="Premium Plan"
                :amount="50"
                icon-1="4 team members"
                icon-2="10gb"
                @click="premiumPlan"
            />
        </div>
    </div>
</template>

<script setup>

import PlanCard from "./PlanCard.vue";
import store from "../../store";

function createSubscription(plan) {
    let data = {
        customerId: store.state.stripe.clientId,
        planName: plan
    }
    console.log(data);
    return;
    store.dispatch('createStripeSubscription', data)
        .then(response => {
            console.log(store.state.stripe.clientId)
            if (response.status === 201) {
                // TODO show notification
                store.dispatch('getRoles')
            }
        })
        .catch(err => {
            disabled.value = false;
            // debugger;
        })
}
const basicPlan = () => {
    const plan = 'BASIC'
    createSubscription(plan)
}

const standardPlan = () => {
    const plan = 'STANDARD'
    createSubscription(plan)
}

const premiumPlan = () => {
    const plan = 'PREMIUM'
    createSubscription(plan)
}

</script>
