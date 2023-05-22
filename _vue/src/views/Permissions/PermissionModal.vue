<!-- This example requires Tailwind CSS v2.0+ -->
<template>

  <v-dialog v-model="show" width="576">
    <v-card>
      <form @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5">
            {{ permission.id ? `Update permission: "${props.permission.name}"` : 'Create new Permission' }}
          </span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row dense="dense">
              <v-col cols="12" sm="12" md="12" class="m-0 p-0" >
                <v-text-field density="compact" v-model="permission.name" :rules="rules" label="Name" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="12" class="m-0 p-0">
                <v-text-field density="compact" v-model="permission.guard_name" :rules="rules" label="Guard name" variant="outlined"></v-text-field>
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
import {computed, onUpdated, ref, watch} from 'vue'
import store from "../../store/index.js";
import {useNotification} from "@kyvg/vue3-notification";

const permission = ref({
  id: props.permission.id,
  name: props.permission.name,
  guard_name: props.permission.guard_name,
})

const permissions = computed(() => store.state.permissions);
const roles = ref([]);
const loading = ref(false)

const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];

const notification = useNotification()

const props = defineProps({
  modelValue: Boolean,
    roles: Array,
  permission: {
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
  permission.value = {
    id: props.permission.id,
    name: props.permission.name,
    guard_name: props.permission.guard_name,
  }
})

watch(show, (first, second) => {
    if (first===false) emit('close')
});

async function onSubmit() {
  const {valid} = await form.value.validate();

  if (!valid) return

  show.value = true;
  loading.value = true;

  const action = permission.value.id ? 'updatePermission' : 'createPermission';

  try {
    const response = await store.dispatch(action, permission.value);
    if ([200, 201].includes(response.status)) {
      notification.notify({title: "Success!", type: "success"});
      await store.dispatch('getPermissions');
    }
    show.value = false;
  } catch (error) {
    notification.notify({title: "Error!", type: "error"});
  } finally {
    loading.value = false;
  }

}
</script>
