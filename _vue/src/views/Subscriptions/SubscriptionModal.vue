<template>
  <v-dialog v-model="show" width="576">
    <v-card>
      <v-form @submit.prevent="onSubmit">
        <v-card-title>
          <span class="text-h5"> {{ subscription.id ? `Update subscription: "${props.subscription.name}"` : 'Create new Subscription' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row dense="dense">
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="subscription.name"  label="Name" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="subscription.email"  label="Email" variant="outlined"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="12">
                <v-text-field density="compact" v-model="subscription.password"  label="Password" variant="outlined"></v-text-field>
              </v-col>

              <v-col cols="12" sm="12" >
                <v-select
                    v-model="subscription.roles"
                    :items="subscriptions.roles.map((item)=>item.name)"
                    chips
                    label="Roles"
                    multiple
                ></v-select>
              </v-col>
              <v-col cols="12" sm="12" >
                <v-select
                    v-model="subscription.permissions"
                    :items="subscriptions.permissions.map((item)=>item.name)"
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

const subscription = ref({
    id: props.subscription.id,
    name: props.subscription.name,
    email: props.subscription.email,
    roles: props.subscription.roles.map((item)=>item.name),
    permissions: props.subscription.permissions.map((item)=>item.name)
})

const subscriptions = computed(() => store.state.subscriptions);
const roles = ref([]);
const permissions = ref([]);
const loading = ref(false)

const props = defineProps({
    modelValue: Boolean,
    roles: Array,
    permissions: Array,
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

const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];


onUpdated(() => {
    subscription.value = {
        id: props.subscription.id,
        name: props.subscription.name,
        email: props.subscription.email,
        roles: props.subscription.roles.map(item => item.name),
        permissions: props.subscription.permissions.map(item => item.name)
    }
    roles.value = props.subscription.roles.map(item => item.name);
    permissions.value = props.subscription.permissions.map(item => item.name);
})

watch(show, (first, second) => {
    if (first===false) emit('close')
});

function onSubmit() {
    show.value = true
    // subscription.value.roles.value = roles
    // subscription.value.permissions.value = permissions
    if (subscription.value.id) {
        store.dispatch('updateSubscription', subscription.value)
            .then(response => {
                loading.value = false;
                if (response.status === 200) {
                    notification.notify({
                        title: "Success!",
                        type: "success",
                    });
                    // TODO show notification
                    store.dispatch('getSubscriptions')
                    show.value=false
                }
            })
            .catch(response=>{
              show.value = true;
            })
    } else {
        store.dispatch('createSubscription', subscription.value)
            .then(response => {
              show.value = false;
                if (response.status === 201) {
                    // TODO show notification
                    store.dispatch('getSubscriptions')
                    notification.notify({
                        title: "Success!",
                        type: "success",
                    });
                    show.value=false
                }
            })
            .catch(err => {
              show.value = true;
                debugger;
            })
    }
}
</script>
