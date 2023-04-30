<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Users</h1>
    <button type="button"

            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new User
    </button>
  </div>

<!--  <v-row>-->
<!--    <v-col-->
<!--        v-for="n in 9"-->
<!--        :key="n"-->
<!--        class="d-flex child-flex"-->
<!--        cols="4"-->
<!--    >-->
<!--      <v-img-->
<!--          :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`"-->
<!--          :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`"-->
<!--          aspect-ratio="1"-->
<!--          cover-->
<!--          class="bg-grey-lighten-2"-->
<!--      >-->
<!--        <template v-slot:placeholder>-->
<!--          <v-row-->
<!--              class="fill-height ma-0"-->
<!--              align="center"-->
<!--              justify="center"-->
<!--          >-->
<!--            <v-progress-circular-->
<!--                indeterminate-->
<!--                color="grey-lighten-5"-->
<!--            ></v-progress-circular>-->
<!--          </v-row>-->
<!--        </template>-->
<!--      </v-img>-->
<!--    </v-col>-->
<!--  </v-row>-->

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";

</script>

<style scoped>

</style>
