<template>
    <ProfileHeader :edit="true"/>
    <BillingDetails class="mt-8"/>
</template>
<script setup>
//importing bootstrap 5 Modules
import "bootstrap/dist/css/bootstrap.min.css";
import BillingDetails from "../Subscribe/BillingDetails.vue";
import ProfileHeader from "./ProfileHeader.vue";

</script>
