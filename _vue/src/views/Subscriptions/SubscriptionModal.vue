<template>
  <v-dialog v-model="show" width="576">
    <v-card>
      <v-form ref="form" fast-fail @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5"> {{ subscription.id ? `Update subscription for: "${props.subscription.contentCreator.title}"` : 'Create new Subscription' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row dense="dense">
              <v-col cols="12" sm="12" >
                <v-autocomplete
                    v-model="subscription.contentCreator"
                    :items="contentCreators"
                    :return-object="true"
                    :rules="autocompleteRules"
                    hide-no-data
                    :menu="showMenuContentCreator"
                    @input="debouncedLoadUsers($event.target.value)"
                    @update:modelValue="debouncedLoadSubscriptionPlans('')"
                    :loading="loadingContentCreators"
                    label="Content creator"
                    ref="autocomplete"
                ></v-autocomplete>
              </v-col>
              <v-col cols="12" sm="12" >
                <v-autocomplete
                    :auto-select-first="true"
                    v-model="subscription.subscriptionPlan"
                    :items="subscriptionPlans"
                    :return-object="true"
                    :rules="autocompleteRules"
                    :menu="showMenuSubscriptionPlans"
                    hide-no-data
                    @input="debouncedLoadSubscriptionPlans($event.target.value)"
                    :loading="loadingSubscriptionPlans"
                    :disabled="!subscription.contentCreator"
                    label="Subscription plan"
                ></v-autocomplete>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue-darken-1" variant="text" @click="show = false"  >
            Close
          </v-btn>
          <v-btn type="submit" color="blue-darken-1" variant="text" :loading="loading"  >
            Save
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script setup>
import {computed, onUpdated, ref, watch} from 'vue'
import store from "../../store/index.js";
import {useNotification} from "@kyvg/vue3-notification";

const notification = useNotification()

const subscription = ref({
  id: props.subscription.id,
  userId: props.subscription.userId,
  contentCreator: props.subscription.contentCreator,
  subscriptionPlan: props.subscription.subscriptionPlan,
})

const subscriptions = computed(() => store.state.subscriptions);
const roles = ref([]);
const permissions = ref([]);
const loading = ref(false)

const props = defineProps({
    modelValue: Boolean,
    subscription: {
        required: true,
        type: Object,
    }
})

const emit = defineEmits(['update:modelValue', 'close'])

const show = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
})
const test = ref(false)

const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];
const autocompleteRules = [
    value => {
      if (value===null || !value) return 'Enter at least one character'
      return true
    }
]

let debounceTimer;

const loadingContentCreators = ref(false)
const contentCreators = ref([])
const showMenuContentCreator = ref(false)

const loadingSubscriptionPlans = ref(false)
const subscriptionPlans = ref([])
const showMenuSubscriptionPlans = ref(false)


const delayedLoadUsers = async (searchValue) => {
  let params = {
    url: null,
    search: searchValue,
    per_page: 20,
    sort_field: 'name',
    sort_direction: 'asc'
  };

  await store.dispatch('getUsers', params);
  loadingContentCreators.value = false;
  contentCreators.value = store.state.users.data.map((user) => ({
    value: user.id,
    title: user.name
  })).filter((element) => element.value !== subscription.value.userId);
  showMenuContentCreator.value = true;
};

// Function to debounce the loadUsers function
const debouncedLoadUsers = async (searchValue) => {
  if (show.value === false || subscription.value.contentCreator?.title === searchValue) return;

  loadingContentCreators.value = true;
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(async () => {
    await delayedLoadUsers(searchValue);
  }, 1000);
};

const debouncedLoadSubscriptionPlans = async (searchValue) => {
  if (show.value === false || !subscription.value.contentCreator) return;
  loadingSubscriptionPlans.value = true;
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(async () => {
    await delayedLoadSubscriptionPlans(searchValue);
  }, 1000);
};

const delayedLoadSubscriptionPlans = async (searchValue) => {
  if (!subscription.value.contentCreator) return;
  let params = {
    url: null,
    search: searchValue,
    per_page: 20,
    sort_field: 'name',
    sort_direction: 'asc',
    id: subscription.value.contentCreator.value
  };

  try {
    loadingSubscriptionPlans.value = true;
    // Make the asynchronous dispatch call
    await store.dispatch('getSubscriptionPlans', params);
    loadingSubscriptionPlans.value = false;

    // Update subscriptionPlans with the retrieved data
    subscriptionPlans.value = store.state.subscriptionPlans.data.map((subscriptionPlan) => ({
      value: subscriptionPlan.id,
      title: subscriptionPlan.name
    }));
    if (subscriptionPlans.value.length===0) {
      subscription.value.subscriptionPlan = ''
    }

    showMenuSubscriptionPlans.value = true;
    console.log(showMenuSubscriptionPlans.value);
  } catch (error) {
    console.error('Error loading subscription plans:', error);
    loadingSubscriptionPlans.value = false;
  }
};

onUpdated(() => {
    subscription.value = {
      id: props.subscription.id,
      userId: props.subscription.userId,
      contentCreator:  props.subscription.contentCreator,
      subscriptionPlan:  props.subscription.subscriptionPlan,
    }
})

watch(show, (value) => {
    if (value===false) {
      emit('close')
      showMenuSubscriptionPlans.value = false
      showMenuContentCreator.value = false
    }
});

async function onSubmit() {
  const {valid} = await form.value.validate();

  if (!valid) return

  show.value = true;
  loading.value = true;

  const action = subscription.value.id ? 'updateSubscription' : 'createSubscription';

  try {
    let params = {}
    params = {
      ...subscription.value,
      contentCreator: subscription.value.contentCreator.value,
      subscriptionPlan: subscription.value.subscriptionPlan.value
    };
    const response = await store.dispatch(action, params);
    if ([200, 201].includes(response.status)) {
      notification.notify({title: "Success!", type: "success"});
      await store.dispatch('getSubscriptions');
    }
    show.value = false;
  } catch (error) {
    notification.notify({title: "Error!", type: "error"});
  } finally {
    loading.value = false;
  }
}
</script>
