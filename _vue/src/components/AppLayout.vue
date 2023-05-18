<template>
  <div v-if="currentUser" class="min-h-full bg-gray-200 flex">
    <v-layout>
      <Sidebar v-if="currentUser.id" :rail=sidebarOpened :user="currentUser" ref="childComponent"/>
      <Navbar @toggle-sidebar="toggleSidebar"/>
      <v-main class="bg-black">

          <router-view></router-view>

      </v-main>
    </v-layout>
  </div>
  <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
    <router-view></router-view>
    <Spinner />
  </div>
</template>

<script setup>
import {ref, computed, onMounted, onUnmounted} from 'vue'
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import store from "../store";
import Spinner from "./core/Spinner.vue";

const {title} = defineProps({
  title: String
})
const sidebarOpened = ref(true);
const currentUser = computed(() => store.state.user.data);

function toggleSidebar() {
  sidebarOpened.value = !sidebarOpened.value
}

onMounted(() => {
  store.dispatch('getCurrentUser')
})


</script>

<style scoped>

</style>
