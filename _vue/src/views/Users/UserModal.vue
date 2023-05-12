<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <v-dialog v-model="show" width="576">
    <v-card>
      <form @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5"> {{ user.id ? `Update user: "${props.user.name}"` : 'Create new User' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="user.name"  label="Name" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="user.email"  label="Email" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="user.password"  label="Password" variant="outlined"></v-text-field>
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
      </form>
    </v-card>
  </v-dialog>
</template>

<script setup>
import {computed, onUpdated, ref} from 'vue'
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

function closeModal() {
    show.value = false
    emit('close')
}

function onSubmit() {
    show.value = true
    // user.value.roles.value = roles
    // user.value.permissions.value = permissions
    if (user.value.id) {
        store.dispatch('updateUser', user.value)
            .then(response => {
                loading.value = false;
                if (response.status === 200) {
                    notification.notify({
                        title: "Success!",
                        type: "success",
                    });
                    // TODO show notification
                    store.dispatch('getUsers')
                    closeModal()
                }
            })
            .catch(response=>{
              show.value = true;
            })
    } else {
        store.dispatch('createUser', user.value)
            .then(response => {
              show.value = false;
                if (response.status === 201) {
                    // TODO show notification
                    store.dispatch('getUsers')
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
