<template>
  <div class="m-4">
    <div class="mb-2 flex items-center justify-between">
      <h1 class="text-3xl font-semibold">Dashboard</h1>
      <div class="flex items-center">

        <v-select
            :items="dateOptions"
            v-model="chosenDate"
            density="compact"
            @update:modelValue="onDatePickerChange()"
        >
          <template  v-slot:prepend>Change Date Period</template>
        </v-select>

      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
      <!--    Active Customers-->
      <div class="animate-fade-in-down bg-dark py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
        <label class="text-lg font-semibold block mb-2">Active Users</label>
        <template v-if="!loading.subscribersCount">
          <span class="text-3xl font-semibold">{{ subscribersCount }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>

      <div class="animate-fade-in-down bg-dark py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center"
           style="animation-delay: 0.1s">
        <label class="text-lg font-semibold block mb-2">Active Subscriptions</label>
        <template v-if="!loading.productsCount">
          <span class="text-3xl font-semibold">{{ productsCount }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>

      <div class="animate-fade-in-down bg-dark py-6 px-5 rounded-lg shadow flex flex-col items-center"
           style="animation-delay: 0.3s">
        <label class="text-lg font-semibold block mb-2">Total Income</label>
        <template v-if="!loading.totalIncome">
          <span class="text-3xl font-semibold">{{ totalIncome }}</span>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
    </div>

    <div class="grid grid-rows-1 md:grid-rows-2 md:grid-flow-col grid-cols-1 md:grid-cols-3 gap-3">
      <div class="col-span-1 md:col-span-2 row-span-1 md:row-span-2 bg-dark py-6 px-5 rounded-lg shadow">
        <label class="text-lg font-semibold block mb-2">Latest Subscriptions</label>
        <template v-if="!loading.latestSubscriptions">
          <div v-for="o of latestSubscriptions" :key="o.id" class="py-2 px-3 hover:bg-black rounded-lg">
            <p>
            <span class="text-light font-semibold">
              Payment #{{ o.id }}
            </span>
              created {{ new Date(o.created_at).toLocaleString("en-US", options) }}. items
            </p>
            <p class="flex justify-between">
              <span>Plan: {{ o.subscription_plan.name }} | User: {{o.user.name}}</span>
              <span>{{ $filters.currencyUSD(o.amount) }}</span>
            </p>
          </div>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
      <div class="bg-dark py-6 px-5 rounded-lg shadow flex flex-col items-center justify-center">
        <label class="text-lg font-semibold block mb-2">Subscriptions by Country</label>
        <template v-if="!loading.subscriptionsByCountry">
          <DoughnutChart :width="140" :height="200" :data="subscriptionsByCountry"/>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
      <div class="bg-dark py-6 px-5 rounded-lg shadow">

        <label class="text-lg font-semibold block mb-2">Latest Customers</label>
        <template v-if="!loading.latestCustomers">
          <router-link :to="{name: 'app.contents', params: {id: c.user.id}}" v-for="c of latestCustomers" :key="c.id"
                       class="mb-3 flex">
            <div class="w-12 h-12 bg-gray-200 flex items-center justify-center rounded-full mr-2">
              <UserIcon class="w-5"/>
            </div>
            <div>
              <h3>{{ c.user.name }}</h3>
              <p>{{ c.user.email }}</p>
            </div>
          </router-link>
        </template>
        <Spinner v-else text="" class=""/>
      </div>
    </div>
  </div>

</template>

<script setup>
import {UserIcon} from '@heroicons/vue/outline'
import DoughnutChart from '../components/core/Charts/Doughnut.vue'
import axiosClient from "../axios.js";
import {computed, onMounted, ref} from "vue";
import Spinner from "../components/core/Spinner.vue";
import {useStore} from "vuex";

const store = useStore();
const dateOptions = computed(() => store.state.dateOptions);
const chosenDate = ref('all')

const loading = ref({
  subscribersCount: true,
  productsCount: true,
  totalIncome: true,
  subscriptionsByCountry: true,
  latestCustomers: true,
  latestSubscriptions: true
})

const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    second: "numeric",
    timeZone: "UTC"
};
const subscribersCount = ref(0);
const productsCount = ref(0);
const totalIncome = ref(0);
const subscriptionsByCountry = ref([]);
const latestCustomers = ref([]);
const latestSubscriptions = ref([]);

function updateDashboard() {
  const d = chosenDate.value
  loading.value = {
    subscribersCount: true,
    productsCount: true,
    totalIncome: true,
    subscriptionsByCountry: true,
    latestCustomers: true,
    latestSubscriptions: true
  }
  axiosClient.get(`/dashboard/subscribers-count`, {params: {d}}).then(({data}) => {
    subscribersCount.value = data
    loading.value.subscribersCount = false;
  })
  axiosClient.get(`/dashboard/products-count`, {params: {d}}).then(({data}) => {
    productsCount.value = data;
    loading.value.productsCount = false;
  })
  axiosClient.get(`/dashboard/income-amount`, {params: {d}}).then(({data}) => {
    totalIncome.value = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 0
    })
        .format(data);
    loading.value.totalIncome = false;
  })
  axiosClient.get(`/dashboard/subscriptions-by-country`, {params: {d}}).then(({data: countries}) => {
    loading.value.subscriptionsByCountry = false;
    const chartData = {
      labels: [],
      datasets: [{
        backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
        data: []
      }]
    }
    countries.forEach(c => {
      chartData.labels.push(c.name);
      chartData.datasets[0].data.push(c.count);
    })
    subscriptionsByCountry.value = chartData
  })
  axiosClient.get(`/dashboard/latest-subscribers`, {params: {d}}).then(({data: subscribers}) => {
    latestCustomers.value = subscribers;
    loading.value.latestCustomers = false;
  })
  axiosClient.get(`/dashboard/latest-subscriptions`, {params: {d}}).then(({data: subscriptions}) => {
    latestSubscriptions.value = subscriptions;
    loading.value.latestSubscriptions = false;
  })
}


function onDatePickerChange() {
  updateDashboard()
}

onMounted(() => updateDashboard())
</script>

<style scoped>

</style>
