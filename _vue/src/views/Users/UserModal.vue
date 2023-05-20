<template>
  <v-dialog v-model="show" width="576">
    <v-card>
      <v-form ref="form" @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5"> {{ user.id ? `Update user: "${props.user.name}"` : 'Create new User' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row dense="dense">
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="user.name" :rules="rules"  label="Name" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="user.email" :rules="rules"  label="Email" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="user.password" label="Password" variant="outlined"></v-text-field>
              </v-col>

              <v-col cols="12" sm="12" >
                <v-select
                    v-model="user.roles"
                    :items="users.roles.map((item)=>item.name)"
                    chips
                    label="Roles"
                    multiple
                ></v-select>
              </v-col>
              <v-col cols="12" sm="12" >
                <v-select
                    v-model="user.permissions"
                    :items="users.permissions.map((item)=>item.name)"
                    chips
                    label="Permissions"
                    multiple
                >
                </v-select>
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

const user = ref({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    roles: props.user.roles.map((item)=>item.name),
    permissions: props.user.permissions.map((item)=>item.name)
})

const users = computed(() => store.state.users);
const roles = ref([]);
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
    roles: Array,
    permissions: Array,
    user: {
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
    user.value = {
        id: props.user.id,
        name: props.user.name,
        email: props.user.email,
        roles: props.user.roles.map(item => item.name),
        permissions: props.user.permissions.map(item => item.name)
    }
    roles.value = props.user.roles.map(item => item.name);
    permissions.value = props.user.permissions.map(item => item.name);
})

watch(show, (first, second) => {
    if (first===false) emit('close')
});

async function onSubmit() {
  show.value = true
  const {valid} = await form.value.validate();

  if (!valid) return

  show.value = true;
  loading.value = true;

  const action = user.value.id ? 'updateUser' : 'createUser';

  try {
    const response = await store.dispatch(action, user.value);

    if ([200, 201].includes(response.status)) {
      notification.notify({ title: "Success!", type: "success" });
      await store.dispatch('getUsers');
    }
    show.value = false;
  } catch (error) {
    notification.notify({ title: "Error!", type: "error" });
  } finally {
    loading.value = false;
  }
}
</script>
