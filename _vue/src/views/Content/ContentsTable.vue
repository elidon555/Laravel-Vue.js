<template>

  <div class="p-4 rounded-lg shadow animate-fade-in-down">
    <br>

    <div class="d-flex flex-column justify-content-center">
      <v-col
          v-for="content in contents.data"
          :key="content.id"
      >
        <div class="max-w-screen-sm m-auto d-flex flex-column mb-2 bg-dark p-2">
          <div v-if="content.url">
            <v-img v-if="content.type==='photo'"
                   @click="openDialog(content)"
                   :lazy-src="content.url"
                   :src="content.url"
                   cover
                   class="p-2 bg-dark"
                   :class = "(content.type==='photo')?'photo':'video'"
            />
            <div v-else-if="content.type==='video'" class="bg-dark">
              <video
                  preload="metadata" controls>
                <source :src="content.url" type="video/mp4">
              </video>
            </div>
          </div>
          <div v-else>
            <div class="grid place-items-center h-auto bg-black cursor-pointer" onclick="window.scrollTo(0,0);">
              <br>
              <br>
              <br>
              <v-icon icon="mdi-lock"></v-icon>
              <span>Unlock this post</span>
              <span>By becoming a supporter</span>
              <br>
              <br>
              <br>
            </div>
          </div>

          <v-card class="bg-dark">
            <v-card-subtitle class="mt-3">{{content.created_at}}</v-card-subtitle>
            <v-card-title >
              {{content.title}}
            </v-card-title>
            <v-card-text >
              <div
                  :class="content.url ? '' : 'bg-gradient-to-b from-white to-black-800 bg-clip-text text-transparent antialiased subpixel-antialiased font-bold'"
              >
                {{content.description}}
              </div>
            </v-card-text>
          </v-card>
        </div>

      </v-col>
    </div>


    <v-dialog
        :scrollable="false"
        scroll-strategy="none"
        v-model="dialog.show"
        height="100%"
        :fullscreen="true"
        persistent
    >
      <v-img v-if="dialog.type==='photo'"
             :lazy-src="dialog.url"
             :src="dialog.url"
             class="max-h-screen"
             @click="dialog.show=false"
      />
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
        <button
            v-for="(link, i) of contents.links"
            :key="i"
            :disabled="!link.url"
            href="#"
            @click="getForPage($event, link)"
            aria-current="page"
            class="relative rounded-lg pointer-events-auto ml-1 mr-1 inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-dark border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md' : '',
              i === contents.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? ' bg-gray-100 text-gray-700': ''
            ]"
            v-html="link.label"
        >
        </button>
      </nav>
    </div>



  </div>

</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import store from "../../store";
import {USERS_PER_PAGE} from "../../constants";
import {useRoute} from "vue-router";
import {useLoading} from 'vue-loading-overlay';

const perPage = ref(USERS_PER_PAGE/2);
const search = ref('');
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const $loading = useLoading({
  container: null,
  canCancel: false,
});


const dialog = ref({
  show:false,
  type:'',
  url: ''
})

const route = useRoute()
const userId = computed(() => route.params.id)

const contents = computed(() => store.state.contents);
const user = computed(() => store.state.user);

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
  const loader = $loading.show({});

  store.dispatch("getContents", {
    url,
    search: search.value,
    per_page: perPage.value,
    id:userId.value || user.value.data.id
  }).then(response=>{
    loader.hide()
  });
}

</script>

<style scoped>

</style>
