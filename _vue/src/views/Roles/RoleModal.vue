<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <v-dialog v-model="show" width="576">
    <v-card>
      <v-form ref="form" @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5"> {{ role.id ? `Update role: "${props.role.name}"` : 'Create new Role' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row dense="dense">
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" :rules="rules" v-model="role.name"  label="Name" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" :rules="rules" v-model="role.guard_name"  label="Guard name" variant="outlined"></v-text-field>
              </v-col>

              <v-col cols="12" sm="12" >
                <v-select
                    v-model="permissions"
                    :items="roles.permissions.map((item)=>item.name)"
                    chips
                    label="Roles"
                    multiple
                ></v-select>
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

const role = ref({
  id: props.role.id,
  name: props.role.name,
  guard_name: props.role.guard_name,
    permissions: props.role.permissions
})

const roles = computed(() => store.state.roles);
const permissions = ref([]);
const loading = ref(false)
const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];

const props = defineProps({
  modelValue: Boolean,
  role: {
    required: true,
    type: Object,
  }
})

const notification = useNotification()


const emit = defineEmits(['update:modelValue', 'close'])

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

onUpdated(() => {
  role.value = {
    id: props.role.id,
    name: props.role.name,
    guard_name: props.role.guard_name,
    permissions: props.role.permissions,
  }
  permissions.value = props.role.permissions.map(item => item.name);

})

watch(show, (first, second) => {
    if (first===false) emit('close')
});

async function onSubmit() {

  const {valid} = await form.value.validate();

  if (!valid) return

  show.value = true;
  loading.value = true;
  role.value.permissions = permissions;

  const action = role.value.id ? 'updateRole' : 'createRole';

  try {
    const response = await store.dispatch(action, role.value);

    if ([200, 201].includes(response.status)) {
      notification.notify({title: "Success!", type: "success"});
      await store.dispatch('getRoles');
    }
    show.value = false;
  } catch (error) {
    notification.notify({title: "Error!", type: "error"});
  } finally {
    loading.value = false;
  }
}
</script>
