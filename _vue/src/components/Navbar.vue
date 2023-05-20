<template>
  <v-app-bar density="comfortable"
  >

    <template v-slot:prepend>
      <v-app-bar-nav-icon @click="emit('toggle-sidebar')"></v-app-bar-nav-icon>
    </template>

    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>
    <v-spacer></v-spacer>


    <v-spacer></v-spacer>

    <router-link :to="{name: 'login'}">
      <v-btn v-if="currentUser.token === null" class="mr-5 pr-5" icon>
        Login
        <v-icon>mdi-login</v-icon>
      </v-btn>
    </router-link>

    <v-menu v-if = "currentUser.token !== null" location="start">
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

            <router-link :to="{name: 'logout'}" >
              <v-list-item @click="logout">
                  <v-list-item-title>
                      <div class="d-flex">
                          Logout
                      </div>
                  </v-list-item-title>
              </v-list-item>
            </router-link>
          </v-list>
      </v-menu>
  </v-app-bar>
</template>

<script setup>
import store from "../store";
import {computed} from "vue";

const emit = defineEmits(['toggle-sidebar'])

const currentUser = computed(() => store.state.user);

function logout() {
  // store.dispatch('logout')
  //   .then(() => {
  //     router.push({name: 'logout'})
  //   })
}

</script>

<style scoped>

</style>
