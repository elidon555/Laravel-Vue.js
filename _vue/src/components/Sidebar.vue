<template>
  <v-navigation-drawer
      v-model="drawer"
      :rail="rail"
      permanent
      @click="rail = false"
  >
    <v-list-item
        prepend-avatar="https://cdn-icons-png.flaticon.com/512/6596/6596121.png"
        title="John Leider"
        nav
    >
      <template v-slot:append>
        <v-btn
            variant="text"
            icon="mdi-chevron-left"
            @click.stop="rail = !rail"
        ></v-btn>
      </template>
    </v-list-item>

    <v-divider></v-divider>

    <v-list density="compact" nav>

      <router-link v-show="checkRole(['user'])" :to="{name: 'app.profile'}">
        <v-list-item prepend-icon="mdi-account" title="My Account" value="account"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['user'])" :to="{name: 'app.contents',params:{id:user.id}}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Contents" value="contents"></v-list-item>
      </router-link>

      <v-divider class="m-2"></v-divider>

      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.dashboard'}">
        <v-list-item prepend-icon="mdi-view-dashboard-outline" title="Dashboard" value="dashboard"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['admin'])" :to="{name: 'reports.subscriptions'}">
        <v-list-item prepend-icon="mdi-chart-bar" title="Reports" value="dashboard"></v-list-item>
      </router-link>

      <v-divider class="m-2"></v-divider>

      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.users'}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['admin','finance'])" :to="{name: 'app.payments'}">
        <v-list-item prepend-icon="mdi-finance" title="Payments" value="payments"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['user'])" :to="{name: 'app.subscriptionPlans'}">
        <v-list-item prepend-icon="mdi-credit-card-outline" title="Subscription plans" value="Subscription plans"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['user'])" :to="{name: 'app.subscriptions'}">
        <v-list-item prepend-icon="mdi-credit-card-multiple-outline" title="Subscriptions" value="Subscriptions"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.roles'}">
        <v-list-item prepend-icon="mdi-security" title="Roles" value="roles"></v-list-item>
      </router-link>

      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.permissions'}">
        <v-list-item prepend-icon="mdi-lock" title="Permissions" value="permissions"></v-list-item>
      </router-link>

    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
  import {ref, watch} from "vue";

  const drawer = ref(true);
  const rail = ref(props.rail);

  watch(() => props.rail, (first, second) => {
    rail.value = !rail.value
  });

  const props = defineProps({
    user: {
      required: true,
      type: Object,
    },
    rail: {
      required:true,
      type:Boolean
    }
  })
  const user = ref(props.user)
  function checkRole(roles) {
    return user.value.roles.some(role => roles.includes(role.name));
  }
</script>

<style scoped>

</style>
