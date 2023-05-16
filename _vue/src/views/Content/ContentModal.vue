<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <v-dialog v-model="show" width="576">
        <v-card>
            <form @submit.prevent="onSubmit">
                <v-card-title>
                    <span class="text-h5"> {{ content.id ? `Update content: "${props.content.title}"` : 'Create new Content' }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6" md="12">
                                <v-text-field v-model="content.title"  label="Title" variant="outlined"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="12">
                                <v-textarea v-model="content.description"  label="Description" variant="outlined"></v-textarea>
                            </v-col>
                            <v-col cols="12" sm="6" md="12">
                                <v-file-input @change="onFileSelected"></v-file-input>
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
import {computed, onMounted, onUpdated, ref, watch} from 'vue'
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import {useNotification} from "@kyvg/vue3-notification";

const props = defineProps({
  modelValue: Boolean,
  content: {
    required: true,
    type: Object,
  }
})

const emit = defineEmits(['update:modelValue', 'close'])

const notification = useNotification()

const roles = ref([]);
const permissions = ref([]);
const loading = ref(false)
const file = ref(false)

const content = ref({
  id: props.content.id,
  title: props.content.title,
  description: props.content.description,
})




const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

onUpdated(() => {
  content.value = {
    title: props.content.title,
    description: props.content.description,
  }
})

function onFileSelected(event) {
  file.value = event.target.files[0];
}

watch(show, (first, second) => {
    if (first===false) emit('close')
});

async function onSubmit() {

  loading.value = true
  const formData = new FormData();
  formData.append('properties[title]', content.value.title);
  formData.append('properties[description]', content.value.description);
  formData.append('file', file.value);

  store.dispatch('createContent', formData)
      .then(response => {
        loading.value = false;
        store.dispatch('getContents')
        notification.notify({
          title: "Success!",
          type: "success",
        });
        show.value= false;

      })
      .catch(err => {
        loading.value = false;
        debugger;
      })
}
</script>

