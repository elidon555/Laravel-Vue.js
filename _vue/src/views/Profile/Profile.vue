<template>
    <ProfileHeader />
    <BillingDetails class="mt-8"/>
</template>
<script>
//importing bootstrap 5 Modules
import "bootstrap/dist/css/bootstrap.min.css";
import BillingDetails from "../Subscribe/BillingDetails.vue";
import ProfileHeader from "./ProfileHeader.vue";
export default {
    components: {ProfileHeader, BillingDetails}
}
</script>
