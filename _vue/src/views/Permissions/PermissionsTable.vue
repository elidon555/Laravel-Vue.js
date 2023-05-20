<template>
  <v-table class="rounded-3">
    <template v-slot:top>
      <div class="flex justify-content-between m-1 p-1">
        <div>
          <v-select
              :items="[5,10,20,50]"
              v-model="perPage"
              density="compact"
              @update:modelValue="getPermissions()"
          >
            <template  v-slot:prepend>Per page</template>
            <template  v-slot:append>Found {{permissions.total}} permissions</template>
          </v-select>
        </div>
        <div>
          <v-text-field
              class="w-48 px-3 py-2"
              @change="getPermissions(null)"
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
      <div v-if="!permissions.loading" class="flex justify-between items-center mt-5">
        <div v-if="permissions.data.length" class=" m-2 pb-3">
          Showing from {{ permissions.from }} to {{ permissions.to }}
        </div>
        <nav
            v-if="permissions.total > permissions.limit"
            class="relative z-0 m-2 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

          <button
              v-for="(link, i) of permissions.links"
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
              i === permissions.links.length - 1 ? 'rounded-r-md' : '',
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
                       @click="sortPermissions('id')">
        ID
      </TableHeaderCell>
      <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortPermissions('name')">
        Name
      </TableHeaderCell>
      <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortPermissions('guard_name')">
        Guard name
      </TableHeaderCell>
      <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortPermissions('created_at')">
        Create Date
      </TableHeaderCell>
      <TableHeaderCell field="actions">
        Actions
      </TableHeaderCell>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="permission in permissions.data"
        :key="permission.id"
    >
      <td>{{ permission.id }}</td>
      <td>{{ permission.name }}</td>
      <td>{{ permission.guard_name }}</td>
      <td>{{ permission.created_at }}</td>
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
            <v-list-item @click="editPermission(permission)">
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
            <v-list-item @click="deletePermission(permission)">
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
import {USERS_PER_PAGE} from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'

const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const permissions = computed(() => store.state.permissions);
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const permission = ref({})
const showPermissionModal = ref(false);

const emit = defineEmits(['clickEdit'])

onMounted(() => {
  getPermissions();
})

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getPermissions(link.url)
}

function getPermissions(url = null) {
  store.dispatch("getPermissions", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortPermissions(field) {
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

  getPermissions()
}

function showAddNewModal() {
  showPermissionModal.value = true
}

function deletePermission(permission) {
  if (!confirm(`Are you sure you want to delete the permission?`)) {
    return
  }
  store.dispatch('deletePermission', permission)
    .then(res => {
      // TODO Show notification
      store.dispatch('getPermissions')
    })
}

function editPermission(p) {
  emit('clickEdit', p)
}
</script>

<style scoped>

</style>
