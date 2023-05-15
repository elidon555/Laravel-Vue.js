<template>

  <v-table class="rounded-3">
    <template v-slot:top>
      <div class="flex justify-content-between m-1 p-1">
        <div>
          <v-select
              :items="[5,10,20,50]"
              v-model="perPage"
              density="compact"
              @update:modelValue="getRoles()"
          >
            <template  v-slot:prepend>Per page</template>
            <template  v-slot:append>Found {{roles.total}} roles</template>
          </v-select>
        </div>
        <div>
          <v-text-field
              class="w-48 px-3 py-2"
              @change="getRoles(null)"
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
      <div v-if="!roles.loading" class="flex justify-between items-center mt-5">
        <div v-if="roles.data.length" class=" m-2 pb-3">
          Showing from {{ roles.from }} to {{ roles.to }}
        </div>
        <nav
            v-if="roles.total > roles.limit"
            class="relative z-0 m-2 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

          <button
              v-for="(link, i) of roles.links"
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
              i === roles.links.length - 1 ? 'rounded-r-md' : '',
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
                       @click="sortRoles('id')">
        ID
      </TableHeaderCell>
      <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortRoles('name')">
        Name
      </TableHeaderCell>
      <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortRoles('guard_name')">
        Guard name
      </TableHeaderCell>
      <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortRoles('created_at')">
        Create Date
      </TableHeaderCell>
      <TableHeaderCell field="actions">
        Actions
      </TableHeaderCell>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="role in roles.data"
        :key="role.id"
    >
      <td>{{ role.id }}</td>
      <td>{{ role.name }}</td>
      <td>{{ role.guard_name }}</td>
      <td>{{ role.created_at }}</td>
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
            <v-list-item @click="editRole(role)">
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
            <v-list-item @click="deleteRole(role)">
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
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import Spinner from "../../components/core/Spinner.vue";
import {USERS_PER_PAGE} from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import RoleModal from "./RoleModal.vue";

const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const roles = computed(() => store.state.roles);
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const role = ref({})
const showRoleModal = ref(false);

const emit = defineEmits(['clickEdit'])

onMounted(() => {
  getRoles();
})

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getRoles(link.url)
}

function getRoles(url = null) {
  store.dispatch("getRoles", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortRoles(field) {
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

  getRoles()
}

function showAddNewModal() {
  showRoleModal.value = true
}

function deleteRole(role) {
    if (!confirm(`Are you sure you want to delete the role?`)) {
    return
  }
  store.dispatch('deleteRole', role)
    .then(res => {
      // TODO Show notification
      store.dispatch('getRoles')
    })
}

function editRole(p) {
  emit('clickEdit', p)
}
</script>

<style scoped>

</style>
