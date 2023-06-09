<template>

  <v-table class="rounded-3">
    <template v-slot:top>
      <div class="flex justify-content-between m-1 p-1">
        <div>
          <v-select
                     :items="[5,10,20,50]"
                     v-model="perPage"
                     density="compact"
                     @update:modelValue="getSubscriptionPlans()"
          >
           <template  v-slot:prepend>Per page</template>
           <template  v-slot:append>Found {{subscriptionPlans.total}} subscription plans</template>
          </v-select>
        </div>
        <div>
          <v-text-field
              class="w-48 px-3 py-2"
              @change="getSubscriptionPlans(null)"
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
      <div v-if="!subscriptionPlans.loading" class="flex justify-between items-center mt-5">
        <div v-if="subscriptionPlans.data.length" class=" m-2 pb-3">
          Showing from {{ subscriptionPlans.from }} to {{ subscriptionPlans.to }}
        </div>
        <nav
            v-if="subscriptionPlans.total > subscriptionPlans.limit"
            class="relative z-0 m-2 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

            <button
              v-for="(link, i) of subscriptionPlans.links"
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
              i === subscriptionPlans.links.length - 1 ? 'rounded-r-md' : '',
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
                       @click="sortSubscriptionPlans('id')">
        ID
      </TableHeaderCell>

      <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptionPlans('name')">
        Name
      </TableHeaderCell>

      <TableHeaderCell field="price" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptionPlans('price')">
        Price
      </TableHeaderCell>

      <TableHeaderCell field="interval" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptionPlans('interval')">
        Interval
      </TableHeaderCell>

      <TableHeaderCell field="features" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptionPlans('features')">
        Features
      </TableHeaderCell>

      <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptionPlans('created_at')">
        Created_at
      </TableHeaderCell>

      <TableHeaderCell
                       @click="sortSubscriptionPlans('created_at')">
        Actions
      </TableHeaderCell>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="subscriptionPlan in subscriptionPlans.data"
        :key="subscriptionPlan.id"
    >
      <td>{{ subscriptionPlan.id }}</td>
      <td>{{ subscriptionPlan.name }}</td>
      <td>{{ subscriptionPlan.price }}</td>
      <td>{{ subscriptionPlan.interval }}</td>
      <td v-html="subscriptionPlan.features"/>
      <td>{{ subscriptionPlan.created_at }}</td>
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
            <v-list-item @click="editSubscriptionPlan(subscriptionPlan)">
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
          </v-list>
        </v-menu>
      </td>
    </tr>
    </tbody>
  </v-table>

  <loading v-model:active="isLoading"
           :can-cancel="true"
           :is-full-page="fullPage"/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {USERS_PER_PAGE} from "../../constants";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';


const isLoading = ref(false);
const fullPage = ref(true);
const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const subscriptionPlans = computed(() => store.state.subscriptionPlans);
const sortField = ref('created_at');
const sortDirection = ref('desc')

const emit = defineEmits(['clickEdit'])


onMounted(() => {
  getSubscriptionPlans();
})

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getSubscriptionPlans(link.url)
}

async function getSubscriptionPlans(url = null) {
    isLoading.value = true;
    try {
        await store.dispatch("getSubscriptionPlans", {
            url,
            search: search.value,
            per_page: perPage.value,
            sort_field: sortField.value,
            sort_direction: sortDirection.value
        });
        isLoading.value = false;
    } catch (error) {
        // Handle error
    }
}

function sortSubscriptionPlans(field) {
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

  getSubscriptionPlans()
}
function editSubscriptionPlan(p) {
  emit('clickEdit', p)
}
</script>

<style scoped>

</style>
