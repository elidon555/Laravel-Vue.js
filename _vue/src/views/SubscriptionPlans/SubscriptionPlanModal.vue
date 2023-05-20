<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <v-dialog v-model="show" width="576">
        <v-card>
            <v-form fast-fail ref="form" @submit.prevent="onSubmit">
                <v-card-title>
                    <span class="text-h5"> {{ subscriptionPlan.id ? `Update Subscription Plan: "${props.subscriptionPlan.name}"` : 'Create new Subscription Plan' }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row dense="dense">
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field v-model="subscriptionPlan.name" :rules="rules"  label="Name" variant="outlined"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                                <v-text-field type="number" v-model="subscriptionPlan.price"  :rules="rules" label="Price" variant="outlined" :disabled="subscriptionPlan.id!==''"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <v-select
                                  :rules="rules"
                                  :disabled="subscriptionPlan.id!==''"
                                  v-model="subscriptionPlan.interval"
                                  variant="outlined"
                                  label="Price interval"
                                  :items="['Monthly', 'Yearly']"
                              ></v-select>
                            </v-col>
                            <v-col cols="12" sm="12" md="12">
                              <QuillEditor contentType="html" v-model:content="subscriptionPlan.features" theme="snow" />
                            </v-col>
                          <v-col cols="12" sm="6" md="12">
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
import {computed, onMounted, onUpdated, ref, watch} from 'vue'
import store from "../../store/index.js";
import {useNotification} from "@kyvg/vue3-notification";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';


const notification = useNotification()

const roles = ref([]);
const permissions = ref([]);
const loading = ref(false)
const file = ref(false)

const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];
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

watch(show, (first, second) => {
    if (first===false) emit('close')
});

async function onSubmit() {
  const { valid } = await form.value.validate();

  if (!valid) return

  show.value = true;
  loading.value = true;

  const action = subscriptionPlan.value.id ? 'updateSubscriptionPlan' : 'createSubscriptionPlan';

  try {
    const response = await store.dispatch(action, subscriptionPlan.value);

    if ([200, 201].includes(response.status)) {
      notification.notify({ title: "Success!", type: "success" });
      await store.dispatch('getSubscriptionPlans');
    }
    show.value = false;
  } catch (error) {
    notification.notify({ title: "Error!", type: "error" });
  } finally {
    loading.value = false;
  }
}
</script>
