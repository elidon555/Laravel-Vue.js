<template>
    <div class="relative">
        <img @click="edit ? updateImage('cover') : null"
            :src="coverPreview || 'https://picsum.photos/1920/1080?image='+Math.floor(Math.random() * 100)"
            class="aspect-[4/1] object-cover w-100"
             :class="edit ? 'cursor-pointer  hover:brightness-75' : ''"
             alt="Wallpaper"/>
        <input ref="coverInput" type="file" class="d-none" @change="onCoverFileSelected">

        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2">
            <div class="aspect-w-1 aspect-h-1 text-center" >
                <img @click="edit ? updateImage('profile') : null"
                    :src="profilePreview || 'https://loremflickr.com/320/240/profile'"
                    alt="Bottom Image"
                    class="object-cover w-[120px] h-[120px] rounded-full"
                     :class="edit ? 'cursor-pointer hover:brightness-75' : ''"/>
                <input ref="profileInput" type="file" class="d-none" @change="onProfileFileSelected">
            </div>
        </div>
    </div>
    <br><br><br>
    <div class="flex justify-center">
        <v-btn @click="onSubmit" color="blue" variant="tonal" :class="loading">Update pictures</v-btn>
    </div>

    <div class="text-center mt-3">
        <h1 class="font-semibold leading-tight text-xl">Lorem ipsum dolor</h1>
        <h6 class="font-thin text-gray-300 mt-3">Nulla facilisi. Nam egestas rutrum ex, eget consequat lacus laoreet in.</h6>
    </div>
</template>
<script setup>
//importing bootstrap 5 Modules
import "bootstrap/dist/css/bootstrap.min.css";
import BillingDetails from "../Subscribe/BillingDetails.vue";
import {inject, provide, ref} from "vue";
import Swal from 'sweetalert2'
import store from "../../store";

const props = defineProps({
    edit:Boolean
})

const edit = props.edit;

const emits = defineEmits([''])

const coverFile = ref(null)
const coverInput = ref(null)
const coverPreview = ref(null)

const profileFile = ref(null)
const profileInput = ref(null)
const profilePreview = ref(null)

const loading = ref(false);

function onCoverFileSelected (event){
    // Handle file selection logic here
    coverFile.value = event.target.files[0];
    coverPreview.value = URL.createObjectURL(event.target.files[0])
}
function onProfileFileSelected (event){
    // Handle file selection logic here
    profileFile.value = event.target.files[0];
    profilePreview.value = URL.createObjectURL(event.target.files[0])
}
function updateImage(action) {
    if (action==='profile') {
        profileInput.value.click()
    } else {
        coverInput.value.click();
    }
}

async function onSubmit() {

    loading.value = true
    const formData = new FormData();
    formData.append('cover', coverFile.value);
    formData.append('profile', profileFile.value);

    store.dispatch('updateUserImages', formData)
        .then(response => {
            loading.value = false;
            store.dispatch('getCurrentUser')
            Swal.fire({
                title: "Success!",
                icon: "success",
            });
        })
        .catch(err => {
            loading.value = false;
            Swal.fire({
                title: "Error!",
                icon: "error",
            });
        })
}

</script>


