<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between border-b-2 pb-3">
      <div class="flex items-center">
        <span class="whitespace-nowrap mr-3">Per Page</span>
        <select @change="getContents(null)" v-model="perPage"
                class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ml-3">Found {{contents.total}} contents</span>
          <select @change="getContents(null)" v-model="fileType"
                  class="appearance-none relative block w-24 ml-3 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
              <option value="photo" selected="selected">Photos</option>
              <option value="video">Video</option>
          </select>
      </div>
      <div>
        <input v-model="search" @change="getContents(null)"
               class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
               placeholder="Type to Search roles">
      </div>
    </div>
    <br>
    <v-row class="w-full ">
      <v-col
          v-for="content in contents.data"
          :key="content.id"
          class="d-flex child-flex"
          cols="3"
      >
        <v-card class="w-100">
          <v-card-title >{{content.title}}</v-card-title>
          <v-card-text>
            <v-img
            @click="openDialog(content)"
            :lazy-src="(content.type==='photo') ? content.url : thumbnail"
            :src="(content.type==='photo') ? content.url : thumbnail"
            aspect-ratio="1"
            cover
            class="bg-grey-lighten-2 rounded-3xl w-auto"
            :class = "(content.type==='photo')?'photo':'video'"
            >
          <template v-slot:placeholder>
            <v-row
                class="fill-height ma-0"
                align="center"
                justify="center"
            >
              <v-progress-circular
                  indeterminate
                  color="grey-lighten-5"
              ></v-progress-circular>
            </v-row>
          </template>
        </v-img>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-dialog
        v-model="dialog.show"
        :width="dialog.type==='photo' ? '70%' : 'auto'"
        max-height="85%"
        max-width="75%"
    >
      <v-card  :title="dialog.title">

        <v-card-text>
          <v-img v-if="dialog.type==='photo'"
              :lazy-src="dialog.url"
              :src="dialog.url"
                 width="500px"
              class="bg-grey-lighten-2 rounded w-100"
          ></v-img>
        <video class="w-auto m-auto" v-if="dialog.type==='video'" poster="http://localhost:8000/storage/contents/4zW1ffGSMAKvJWEQRqjhMDZUHMMQFrFob3IY82la.png" width="320" height="240" preload="none" controls>
            <source :src="dialog.url" type="video/mp4">
        </video>
            <v-card-actions class="bg-gray-50 px-2 sm:px-6 sm:flex sm:flex-row-reverse">
                <v-btn color="red" variant="elevated" class="mt-3 ml-3">
                    Delete
                </v-btn>
            </v-card-actions>
        </v-card-text>

      </v-card>
    </v-dialog>

    <div v-if="!contents.loading" class="flex justify-between items-center mt-5">
      <div v-if="contents.data.length">
        Showing from {{ contents.from }} to {{ contents.to }}
      </div>
      <nav
          v-if="contents.total > contents.limit"
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
      >
        <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
        <a
            v-for="(link, i) of contents.links"
            :key="i"
            :disabled="!link.url"
            href="#"
            @click="getForPage($event, link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md' : '',
              i === contents.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? ' bg-gray-100 text-gray-700': ''
            ]"
            v-html="link.label"
        >
        </a>
      </nav>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {USERS_PER_PAGE} from "../../constants";
import Img1 from "../../assets/thumbnail-video.png"
import {right} from "vue-multiselect/dist/vue3-multiselect.common";

const perPage = ref(USERS_PER_PAGE);
const fileType = ref('photo');
const search = ref('');
const sortField = ref('updated_at');
const sortDirection = ref('desc')
const thumbnail = Img1

const dialog = ref({
  show:false,
  type:'',
  url: ''
})

const contents = computed(() => store.state.contents);

function openDialog(content) {
    dialog.value.show = true;
    dialog.value.url = content.url;
    dialog.value.type = content.type;
    dialog.value.title = content.title
}

onMounted(() => {
  getContents();
})

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getContents(link.url)
}

function getContents(url = null) {
  store.dispatch("getContents", {
    url,
    search: search.value,
    per_page: perPage.value,
    file_type: fileType.value
  });
}

function isImagePath(path) {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp'];
    const extension = path.substring(path.lastIndexOf('.')).toLowerCase();
    return imageExtensions.includes(extension);
}
</script>

<style scoped>

</style>
