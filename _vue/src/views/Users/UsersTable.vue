<template>

  <v-table class="rounded-3">
    <template v-slot:top>
      <div class="flex justify-content-between m-1 p-1">
        <div>
          <v-select
                     :items="[5,10,20,50]"
                     v-model="perPage"
                     density="compact"
                     @update:modelValue="getUsers()"
          >
           <template  v-slot:prepend>Per page</template>
           <template  v-slot:append>Found {{users.total}} users</template>
          </v-select>
        </div>
        <div>
          <v-text-field
              class="w-48 px-3 py-2"
              @change="getUsers(null)"
              v-model="search"
              density="compact"
              variant="underlined"
              label="Search templates"
              append-inner-icon="mdi-magnify"
              single-line
              hide-details
          ></v-text-field>
        </div>
      </div>

    </template>
    <template v-slot:bottom>
      <div v-if="!users.loading" class="flex justify-between items-center mt-5">
        <div v-if="users.data.length" class=" m-2 pb-3">
          Showing from {{ users.from }} to {{ users.to }}
        </div>
        <nav
            v-if="users.total > users.limit"
            class="relative z-0 m-2 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

            <button
              v-for="(link, i) of users.links"
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
              i === users.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? ' bg-gray-100 text-gray-700': ''
            ]"
              v-html="link.label"
          >
          </button>
        </nav>
      </div>
    </template>

    <thead>
    <tr>
      <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortUsers('id')">
        ID
      </TableHeaderCell>
      <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortUsers('name')">
        Name
      </TableHeaderCell>
      <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortUsers('email')">
        Email
      </TableHeaderCell>
      <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortUsers('created_at')">
        Create Date
      </TableHeaderCell>
      <TableHeaderCell field="actions">
        Actions
      </TableHeaderCell>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="user in users.data"
        :key="user.id"
    >
      <td>{{ user.id }}</td>
      <td>{{ user.name }}</td>
      <td>{{ user.email }}</td>
      <td>{{ user.created_at }}</td>
      <td>
        <v-menu location="start">
          <template v-slot:activator="{ props }">
            <v-btn
                variant="plain"
                v-bind="props"
                rounded="rounded"
            >
              <DotsVerticalIcon
                  class="h-5 w-5 text-indigo-500"
                  aria-hidden="true"/>
            </v-btn>
          </template>

          <v-list>
            <v-list-item @click="editUser(user)">
              <v-list-item-title>
                <div class="d-flex">
                  <PencilIcon
                      class="mr-2 h-6 w-5 text-indigo-400"
                      aria-hidden="true"
                  />
                  <span>Edit</span>
                </div>

                </v-list-item-title>
            </v-list-item>
            <v-list-item @click="deleteUser(user)">
              <v-list-item-title>
                <div class="d-flex">
                  <TrashIcon
                      class="mr-2 h-6 w-5 text-indigo-400"
                      aria-hidden="true"
                  />
                  Delete
                </div>
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </td>
    </tr>
    </tbody>
  </v-table>

  <loading v-model:active="isLoading"
           :can-cancel="true"
           :on-cancel="onCancel"
           :is-full-page="fullPage"/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {USERS_PER_PAGE} from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import * as notification from "@kyvg/vue3-notification";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';


const isLoading = ref(false);
const fullPage = ref(true);
const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const users = computed(() => store.state.users);
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const user = ref({})
const showUserModal = ref(false);

const emit = defineEmits(['clickEdit'])


onMounted(() => {
  getUsers();
})

function onCancel() {
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getUsers(link.url)
}

function getUsers(url = null) {
  isLoading.value = true;
  store.dispatch("getUsers", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  }).then(function(response){
      isLoading.value = false;
  });
}

function sortUsers(field) {
  if (field === sortField.value) {
    if (sortDirection.value === 'desc') {
      sortDirection.value = 'asc'
    } else {
      sortDirection.value = 'desc'
    }
  } else {
    sortField.value = field;
    sortDirection.value = 'asc'
  }

  getUsers()
}

function showAddNewModal() {
  showUserModal.value = true
}

async function deleteUser(user) {
    if (!confirm(`Are you sure you want to delete the user?`)) {
        return;
    }

    try {
        await store.dispatch('deleteUser', user.id);
        await store.dispatch('getUsers');
        notification.notify({
            title: "Success!",
            type: "success",
        });
    } catch (error) {
        // Handle error
    }
}

function editUser(p) {
  emit('clickEdit', p)
}
</script>

<style scoped>

</style>
