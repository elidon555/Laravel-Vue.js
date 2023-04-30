<template>
  <div class="min-w-[160px] w-[160px]  transition-all bg-indigo-700 text-white py-4 px-2">
    <router-link :to="{name: 'app.dashboard'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <HomeIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Dashboard
      </span>
    </router-link>
    <router-link :to="{name: 'app.products'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ViewListIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Products
      </span>
    </router-link>
    <router-link :to="{name: 'app.orders'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ViewListIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Orders
      </span>
    </router-link>
    <router-link v-show="checkRole(['user'])" :to="{name: 'app.content'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ViewListIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Content
      </span>
    </router-link>
    <router-link v-show="checkRole(['admin'])" :to="{name: 'app.users'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <UsersIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Users
      </span>
    </router-link>
      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.roles'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <UsersIcon class="w-5"/>
      </span>
          <span class="text-xs">
        Roles
      </span>
      </router-link>
      <router-link v-show="checkRole(['admin'])" :to="{name: 'app.permissions'}"
                   class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <UsersIcon class="w-5"/>
      </span>
          <span class="text-xs">
        Permissions
      </span>
      </router-link>
    <router-link :to="{name: 'app.customers'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <UserGroupIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Customers
      </span>
    </router-link>
    <router-link :to="{name: 'reports.orders'}"
                 class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-300">
        <ChartBarIcon class="w-5"/>
      </span>
      <span class="text-xs">
        Reports
      </span>
    </router-link>
  </div>
</template>

<script setup>
  import {HomeIcon, UserGroupIcon, UsersIcon, ViewListIcon, ChartBarIcon} from '@heroicons/vue/outline'
  import {ref} from "vue";

  const props = defineProps({
    user: {
      required: true,
      type: Object,
    }
  })
  const user = ref(props.user)
  function checkRole(roles) {
    return user.value.roles.some(role => roles.includes(role.name));
  }
</script>

<style scoped>

</style>
