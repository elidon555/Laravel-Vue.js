<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-10" @close="show = false">
            <TransitionChild as="template">
                <div class="fixed inset-0 bg-black opacity-70"/>
            </TransitionChild>

            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                                     enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                     leave-from="opacity-100 translate-y-0 sm:scale-100"
                                     leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-[700px] sm:w-full">
                            <Spinner v-if="loading"
                                     class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center"/>
                            <header class="py-3 px-4 flex justify-between items-center">
                                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                                    {{ user.id ? `Update user: "${props.user.name}"` : 'Create new User' }}
                                </DialogTitle>
                                <v-btn icon="mdi-close" variant="text" @click="closeModal()"></v-btn>
                            </header>
                            <form @submit.prevent="onSubmit">
                                <div class="bg-white px-4 pt-5 pb-4">
                                    <v-text-field density="compact" v-model="user.name"  label="Name" variant="outlined"></v-text-field>
                                    <v-text-field density="compact" v-model="user.email"  label="Email" variant="outlined"></v-text-field>
                                    <v-text-field density="compact" v-model="user.password"  label="Password" variant="outlined"></v-text-field>
                                    <hr>
                                    <br>
                                  <v-select
                                      v-model="roles"
                                      :items="users.roles.map((item)=>item.name)"
                                      chips
                                      label="Roles"
                                      multiple
                                  ></v-select>
                                  <hr>
                                  <br>
                                  <v-select
                                      v-model="permissions"
                                      :items="users.permissions.map((item)=>item.name)"
                                      chips
                                      label="Permissions"
                                      multiple
                                  ></v-select>

                                    <footer class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <v-btn color="green" variant="elevated" class="mt-3 ml-3" type="submit">
                                            Submit
                                        </v-btn>
                                        <v-btn variant="tonal" class="mt-3" @click="closeModal" ref="cancelButtonRef">
                                            Cancel
                                        </v-btn>
                                    </footer>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {computed, onMounted, onUpdated, ref} from 'vue'
import {
    Dialog,
    DialogPanel,
    DialogTitle, Listbox, ListboxButton, ListboxOption, ListboxOptions,
    Switch,
    SwitchGroup,
    SwitchLabel,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
// import {ExclamationIcon} from '@heroicons/vue/outline'
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import { CheckIcon, ChevronDoubleUpIcon } from '@heroicons/vue/solid'
import {useNotification} from "@kyvg/vue3-notification";

const notification = useNotification()

const user = ref({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
    roles: props.user.roles,
    permissions: props.user.permissions
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
        roles: props.user.roles,
        permissions: props.user.permissions
    }
    roles.value = props.user.roles.map(item => item.name);
    permissions.value = props.user.permissions.map(item => item.name);
})

function closeModal() {
    show.value = false
    emit('close')
}

function onSubmit() {

    loading.value = true
    user.value.roles = roles
    user.value.permissions = permissions
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
    } else {
        store.dispatch('createUser', user.value)
            .then(response => {
                loading.value = false;
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
                loading.value = false;
                debugger;
            })
    }
}
</script>
