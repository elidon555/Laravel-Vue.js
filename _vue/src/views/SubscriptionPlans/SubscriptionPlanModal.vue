<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <v-dialog v-model="show" width="576">
        <v-card>
            <form @submit.prevent="onSubmit">
                <v-card-title>
                    <span class="text-h5"> {{ subscriptionPlan.id ? `Update Subscription Plan: "${props.subscriptionPlan.title}"` : 'Create new Subscription Plan' }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6" md="12">
                                <v-text-field v-model="subscriptionPlan.name"  label="Name" variant="outlined"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="12">
                                <v-text-field type="number" v-model="subscriptionPlan.price"  label="Price" variant="outlined"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="12">
                              <v-select
                                  v-model="subscriptionPlan.interval"
                                  variant="outlined"
                                  label="Price interval"
                                  :items="['Monthly', 'Yearly']"
                              ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6" md="12">
                              <v-text-field v-model="subscriptionPlan.features"  label="Features" variant="outlined"></v-text-field>
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
            </form>
        </v-card>
    </v-dialog>


</template>

<script setup>
import {computed, onMounted, onUpdated, ref} from 'vue'
import store from "../../store/index.js";
import {useNotification} from "@kyvg/vue3-notification";

const notification = useNotification()

const roles = ref([]);
const permissions = ref([]);
const loading = ref(false)
const file = ref(false)

const subscriptionPlan = ref({
  id: props.subscriptionPlan.id,
  name: props.subscriptionPlan.name,
  price: props.subscriptionPlan.price,
  interval: props.subscriptionPlan.interval,
  features:props.subscriptionPlan.features
})

const props = defineProps({
  modelValue: Boolean,
  subscriptionPlan: {
    required: true,
    type: Object,
  }
})

const emit = defineEmits(['update:modelValue', 'close'])

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

onUpdated(() => {
  subscriptionPlan.value = {
    id: props.subscriptionPlan.id,
    name: props.subscriptionPlan.name,
    price: props.subscriptionPlan.price,
    interval: props.subscriptionPlan.interval,
    features: props.subscriptionPlan.features,
  }
})

function closeModal() {
  show.value = false
  emit('close')
}

function onSubmit() {
  console.log(subscriptionPlan)
  show.value = true
  // subscriptionPlan.value.roles.value = roles
  // subscriptionPlan.value.permissions.value = permissions
  if (subscriptionPlan.value.id) {
    store.dispatch('updateSubscriptionPlan', subscriptionPlan.value)
        .then(response => {
          loading.value = false;
          if (response.status === 200) {
            notification.notify({
              title: "Success!",
              type: "success",
            });
            // TODO show notification
            store.dispatch('getSubscriptionPlans')
            closeModal()
          }
        })
        .catch(response=>{
          show.value = true;
        })
  } else {
    store.dispatch('createSubscriptionPlan', subscriptionPlan.value)
        .then(response => {
          show.value = false;
          if (response.status === 201) {
            // TODO show notification
            store.dispatch('getSubscriptionPlans')
            notification.notify({
              title: "Success!",
              type: "success",
            });
            closeModal()
          }
        })
        .catch(err => {
          show.value = true;
          debugger;
        })
  }
}
</script>
