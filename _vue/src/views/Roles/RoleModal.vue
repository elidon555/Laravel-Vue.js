<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <v-dialog v-model="show" width="576">
    <v-card>
      <form @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5"> {{ role.id ? `Update role: "${props.role.name}"` : 'Create new Role' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="role.name"  label="Name" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="role.guard_name"  label="Guard name" variant="outlined"></v-text-field>
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
      </form>
    </v-card>
  </v-dialog>

</template>

<script setup>
import {computed, onUpdated, ref, watch} from 'vue'

import store from "../../store/index.js";

const role = ref({
  id: props.role.id,
  name: props.role.name,
  guard_name: props.role.guard_name,
    permissions: props.role.permissions
})

const roles = computed(() => store.state.roles);
const permissions = ref([]);
const loading = ref(false)

const props = defineProps({
  modelValue: Boolean,
  role: {
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

function onSubmit() {
  loading.value = true
    role.value.permissions= permissions;
  if (role.value.id) {
    store.dispatch('updateRole', role.value)
      .then(response => {
        loading.value = false;
        if (response.status === 200) {
          // TODO show notification
          store.dispatch('getRoles')
            show.value=false
        }
      })
  } else {
    store.dispatch('createRole', role.value)
      .then(response => {
        loading.value = false;
        if (response.status === 201) {
          // TODO show notification
          store.dispatch('getRoles')
            show.value=false
        }
      })
      .catch(err => {
        loading.value = false;
        debugger;
      })
  }
}
</script>
