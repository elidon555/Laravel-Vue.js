<template>

  <v-table class="rounded-3">
    <template v-slot:top>
      <div class="flex justify-content-between m-1 p-1">
        <div>
          <v-select
                     :items="[5,10,20,50]"
                     v-model="perPage"
                     density="compact"
                     @update:modelValue="getSubscriptions()"
          >
           <template  v-slot:prepend>Per page</template>
           <template  v-slot:append>Found {{subscriptions.total}} subscriptions</template>
          </v-select>
        </div>
        <div>
          <v-text-field
              class="w-48 px-3 py-2"
              @change="getSubscriptions(null)"
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
      <div v-if="!subscriptions.loading" class="flex justify-between items-center mt-5">
        <div v-if="subscriptions.data.length" class=" m-2 pb-3">
          Showing from {{ subscriptions.from }} to {{ subscriptions.to }}
        </div>
        <nav
            v-if="subscriptions.total > subscriptions.limit"
            class="relative z-0 m-2 inline-flex justify-center rounded-md shadow-sm -space-x-px"
            aria-label="Pagination"
        >
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

            <button
              v-for="(link, i) of subscriptions.links"
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
              i === subscriptions.links.length - 1 ? 'rounded-r-md' : '',
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
                       @click="sortSubscriptions('id')">
        ID
      </TableHeaderCell>
      <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptions('name')">
        Name
      </TableHeaderCell>
      <TableHeaderCell field="stripe_id" :sort-field="sortField" :sort-direction="sortDirection"
                       >
        Subbed User
      </TableHeaderCell>
      <TableHeaderCell field="stripe_id" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptions('stripe_id')">
        Plan name
      </TableHeaderCell>
      <TableHeaderCell field="stripe_id" :sort-field="sortField" :sort-direction="sortDirection"
                      >
        Plan price
      </TableHeaderCell>
      <TableHeaderCell field="stripe_id" :sort-field="sortField" :sort-direction="sortDirection"
                       >
        Stripe Id
      </TableHeaderCell>
      <TableHeaderCell field="price_id" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptions('price_id')">
        Price Id
      </TableHeaderCell>
      <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                       @click="sortSubscriptions('created_at')">
        Created at
      </TableHeaderCell>
      <TableHeaderCell field="actions">
        Actions
      </TableHeaderCell>
    </tr>
    </thead>
    <tbody>
    <tr
        v-for="subscription in subscriptions.data"
        :key="subscription.id"
    >
      <td>{{ subscription.id }}</td>
      <td>{{ subscription.user.name }}</td>
      <td>{{ subscription.subscribed_user.name }}</td>
      <td>{{ subscription.plan.name }}</td>
      <td>${{ subscription.plan.amount }}</td>
      <td>{{ subscription.stripe_id }}</td>
      <td>{{ subscription.price_id }}</td>
      <td>{{ subscription.created_at }}</td>
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
            <v-list-item @click="editSubscription(subscription)">
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
            <v-list-item @click="deleteSubscription(subscription)">
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
const subscriptions = computed(() => store.state.subscriptions);
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const subscription = ref({})
const showSubscriptionModal = ref(false);

const emit = defineEmits(['clickEdit'])


onMounted(() => {
  getSubscriptions();
})

function onCancel() {
    console.log('Subscription cancelled the loader.')
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getSubscriptions(link.url)
}

function getSubscriptions(url = null) {
  isLoading.value = true;
  store.dispatch("getSubscriptions", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  }).then(function(response){
      isLoading.value = false;
  });
}

function sortSubscriptions(field) {
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

  getSubscriptions()
}

function showAddNewModal() {
  showSubscriptionModal.value = true
}

function deleteSubscription(subscription) {
  if (!confirm(`Are you sure you want to delete the subscription?`)) {
    return
  }
  store.dispatch('deleteSubscription', subscription.id)
    .then(res => {
      store.dispatch('getSubscriptions')
        notification.notify({
            title: "Success!",
            type: "success",
        });
    })
}

function editSubscription(p) {
  emit('clickEdit', p)
}
</script>

<style scoped>

</style>
