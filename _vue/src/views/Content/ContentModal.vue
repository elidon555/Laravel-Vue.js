<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <v-dialog scroll-strategy="none" v-model="show" width="576">
        <v-card>
            <v-form ref="form" @submit.prevent="onSubmit">
                <v-card-title>
                    <span class="text-h5"> {{ content.id ? `Update content: "${props.content.title}"` : 'Create new Content' }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="12" md="12" class="m-0 p-0">
                                <v-text-field v-model="content.title" :rules="rules" label="Title" variant="outlined"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="12" class="m-0 p-0">
                                <v-textarea v-model="content.description" :rules="rules" label="Description" variant="outlined"></v-textarea>
                            </v-col>
                          <v-radio-group
                              v-model="content.isPublic"
                              inline
                          >
                            <v-radio
                                label="Private"
                                :value="false"
                            ></v-radio>
                            <v-radio
                                label="Public"
                                :value="true"
                            ></v-radio>

                          </v-radio-group>
                            <v-col cols="12" sm="12" md="12" class="m-0 p-0">
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
            </v-form>
        </v-card>
    </v-dialog>


</template>

<script setup>
import {computed, onUpdated, ref, watch} from 'vue'
import store from "../../store/index.js";
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
const form = ref();
const rules = [
  value => {
    if (value) return true
    return 'Field is empty.'
  },
];

const content = ref({
  id: props.content.id,
  title: props.content.title,
  description: props.content.description,
  isPublic: props.content.isPublic,
})




const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

onUpdated(() => {
  content.value = {
    title: props.content.title,
    description: props.content.description,
    isPublic: props.content.isPublic,
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
  formData.append('properties[isPublic]', content.value.isPublic);
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

