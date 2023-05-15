<template>
<!--  <div class="min-w-[160px] w-[160px]  transition-all bg-indigo-700 text-white py-4 px-2">-->
<!--    <router-link :to="{name: 'app.products'}"-->
<!--                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <ViewListIcon class="w-5"/>-->
<!--      </span>-->
<!--      <span class="text-xs">-->
<!--        Products-->
<!--      </span>-->
<!--    </router-link>-->
<!--    <router-link :to="{name: 'app.orders'}"-->
<!--                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <ViewListIcon class="w-5"/>-->
<!--      </span>-->
<!--      <span class="text-xs">-->
<!--        Orders-->
<!--      </span>-->
<!--    </router-link>-->
<!--    <router-link v-show="checkRole(['user'])" :to="{name: 'app.contents'}"-->
<!--                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <ViewListIcon class="w-5"/>-->
<!--      </span>-->
<!--      <span class="text-xs">-->
<!--        Content-->
<!--      </span>-->
<!--    </router-link>-->
<!--    <router-link v-show="checkRole(['admin'])" :to="{name: 'app.users'}"-->
<!--                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <UsersIcon class="w-5"/>-->
<!--      </span>-->
<!--      <span class="text-xs">-->
<!--        Users-->
<!--      </span>-->
<!--    </router-link>-->
<!--      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.roles'}"-->
<!--                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <UsersIcon class="w-5"/>-->
<!--      </span>-->
<!--          <span class="text-xs">-->
<!--        Roles-->
<!--      </span>-->
<!--      </router-link>-->
<!--      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.permissions'}"-->
<!--                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <UsersIcon class="w-5"/>-->
<!--      </span>-->
<!--          <span class="text-xs">-->
<!--        Permissions-->
<!--      </span>-->
<!--      </router-link>-->
<!--    <router-link :to="{name: 'app.customers'}"-->
<!--                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <UserGroupIcon class="w-5"/>-->
<!--      </span>-->
<!--      <span class="text-xs">-->
<!--        Customers-->
<!--      </span>-->
<!--    </router-link>-->
<!--    <router-link :to="{name: 'reports.orders'}"-->
<!--                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">-->
<!--      <span class="mr-2 text-gray-300">-->
<!--        <ChartBarIcon class="w-5"/>-->
<!--      </span>-->
<!--      <span class="text-xs">-->
<!--        Reports-->
<!--      </span>-->
<!--    </router-link>-->
<!--  </div>-->

  <v-navigation-drawer
      v-model="drawer"
      :rail="rail"
      permanent
      @click="rail = false"
  >
    <v-list-item
        prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
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

      <v-list-item prepend-icon="mdi-home-city" title="Home" value="home"></v-list-item>
      <v-list-item prepend-icon="mdi-account" title="My Account" value="account"></v-list-item>

      <router-link v-show="checkRole(['user'])" :to="{name: 'app.contents',params:{id:user.id}}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Contents" value="contents"></v-list-item>
      </router-link>
      <v-divider class="m-2"></v-divider>

        <router-link v-show="checkRole(['admin'])" :to="{name: 'app.dashboard'}">
            <v-list-item prepend-icon="mdi-view-dashboard-outline" title="Dashboard" value="dashboard"></v-list-item>
        </router-link>

      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.users'}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Users" value="users"></v-list-item>
      </router-link>
      <router-link v-show="checkRole(['user'])" :to="{name: 'app.subscriptionPlans'}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Subscription plans" value="Subscription plans"></v-list-item>
      </router-link>
      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.roles'}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Roles" value="roles"></v-list-item>
      </router-link>
      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.permissions'}">
        <v-list-item prepend-icon="mdi-account-group-outline" title="Permissions" value="permissions"></v-list-item>
      </router-link>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
  import {ref, watch} from "vue";
  import store from "../store";

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
