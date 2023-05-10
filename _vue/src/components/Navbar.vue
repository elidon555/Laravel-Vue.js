<template>
<!--  <header class="flex justify-between items-center p-3 h-14 shadow bg-white">-->
<!--    <button @click="emit('toggle-sidebar')"-->
<!--            class="flex items-center justify-center rounded transition-colors w-8 h-8 text-gray-700 hover:bg-black/10">-->
<!--      <MenuIcon class="w-6"/>-->
<!--    </button>-->

<!--  </header>-->
  <v-app-bar density="comfortable"
  >

    <template v-slot:prepend>
      <v-app-bar-nav-icon @click="emit('toggle-sidebar')"></v-app-bar-nav-icon>
    </template>

    <v-app-bar-title>Title</v-app-bar-title>

    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>


    <v-spacer></v-spacer>


    <router-link :to="{name: 'login'}">
      <v-btn v-if="currentUser.id === undefined" class="mr-5" icon>
        Login
        <v-icon>mdi-login</v-icon>
      </v-btn>
    </router-link>

    <v-menu v-if = "currentUser.id" location="start">
          <template v-slot:activator="{ props }">
              <v-btn
                  v-bind="props"
                  icon>
                  <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
          </template>

          <v-list>
              <router-link :to="{name: 'app.profile'}" >
                  <v-list-item>
                          <v-list-item-title>
                              <div class="d-flex">
                                  <span>Profile</span>
                              </div>
                          </v-list-item-title>
                  </v-list-item>
              </router-link>

              <router-link :to="{name: 'app.subscribe'}" >
                  <v-list-item>
                      <v-list-item-title >
                          <div class="d-flex">
                              <span>Subscribe</span>
                          </div>
                      </v-list-item-title>
                  </v-list-item>
              </router-link>

              <v-list-item @click="logout">
                  <v-list-item-title>
                      <div class="d-flex">
                          Logout
                      </div>
                  </v-list-item-title>
              </v-list-item>
          </v-list>
      </v-menu>
  </v-app-bar>
</template>

<script setup>
import {MenuIcon, LogoutIcon, UserIcon} from '@heroicons/vue/outline'
import {Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue'
import {ChevronDownIcon} from '@heroicons/vue/solid'
import store from "../store";
import router from "../router";
import {computed} from "vue";

const emit = defineEmits(['toggle-sidebar'])

const currentUser = computed(() => store.state.user.data);

function logout() {
  store.dispatch('logout')
    .then(() => {
      router.push({name: 'login'})
    })
}

</script>

<style scoped>

</style>
