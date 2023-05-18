<template>

  <v-table class="rounded-3">
    <template v-slot:top>
      <div class="flex justify-content-between m-1 p-1">
        <div>
          <v-select
                     :items="[5,10,20,50]"
                     v-model="perPage"
                     density="compact"
                     @update:modelValue="getPayments()"
          >
           <template  v-slot:prepend>Per page</template>
           <template  v-slot:append>Found {{payments.total}} payments</template>
          </v-select>
        </div>
        <div>
          <v-text-field
              class="w-48 px-3 py-2"
              @change="getPayments(null)"
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
      <div v-if="!payments.loading" class="flex justify-between items-center mt-5">
        <div v-if="payments.data.length" class=" m-2 pb-3">
          Showing from {{ payments.from }} to {{ payments.to }}
        </div>
        <nav
            v-if="payments.total > payments.limit"
            class="relative z-0 m-2 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

            <button
              v-for="(link, i) of payments.links"
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
              i === payments.links.length - 1 ? 'rounded-r-md' : '',
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
                       @click="sortPayments('id')">
        ID
      </TableHeaderCell>
      <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                       >
        Name
      </TableHeaderCell>
      <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection"
                       >
        Price Id
      </TableHeaderCell>
      <TableHeaderCell field="amount" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortPayments('amount')">
        Amount
      </TableHeaderCell>
      <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortPayments('created_at')">
        Create Date
      </TableHeaderCell>
      <TableHeaderCell field="actions">
        Actions
      </TableHeaderCell>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="payment in payments.data"
        :key="payment.id"
    >
      <td>{{ payment.id }}</td>
      <td>{{ payment.user.name }}</td>
      <td>{{ payment.price_id }}</td>
      <td>${{ payment.amount }}</td>
      <td>{{ payment.created_at }}</td>
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
            <v-list-item @click="deletePayment(payment)">
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
const payments = computed(() => store.state.payments);
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const payment = ref({})

const emit = defineEmits(['clickEdit'])


onMounted(() => {
  getPayments();
})
function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getPayments(link.url)
}

function getPayments(url = null) {
  isLoading.value = true;
  store.dispatch("getPayments", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  }).then(function(response){
      isLoading.value = false;
  });
}

function sortPayments(field) {
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

  getPayments()
}

function deletePayment(payment) {
  if (!confirm(`Are you sure you want to delete the payment?`)) {
    return
  }
  store.dispatch('deletePayment', payment)
    .then(res => {
      store.dispatch('getPayments')
        notification.notify({
            title: "Success!",
            type: "success",
        });
    })
}

</script>

<style scoped>

</style>
