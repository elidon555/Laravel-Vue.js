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
                @click=""
            />
            <PlanCard
                title="Premium Plan"
                :amount="10"
                icon-1="4 team members"
                icon-2="10gb"
                @click=""
            />
            <PlanCard
                title="Premium Plan"
                :amount="10"
                icon-1="4 team members"
                icon-2="10gb"
                @click=""
            />
        </div>
    </div>
</template>

<script setup>

import PlanCard from "./PlanCard.vue";
import store from "../../store";

function getSubscriptionPlans(url = null) {
    store.dispatch("getUsers", {
        url,
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
    });
}

</script>
