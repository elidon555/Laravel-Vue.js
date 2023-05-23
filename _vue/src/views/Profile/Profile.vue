<template>
    <ProfileHeader :edit="true" :images="media" :key="$route.path"/>
    <BillingDetails class="mt-8"/>
</template>
<script setup>
//importing bootstrap 5 Modules
import "bootstrap/dist/css/bootstrap.min.css";
import BillingDetails from "../Subscribe/BillingDetails.vue";
import ProfileHeader from "./ProfileHeader.vue";
import {computed} from "vue";
import store from "../../store";

const media = computed(()=>store.state.user.data.media)

</script>
