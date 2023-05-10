<template>

    <div v-if="store.state.user.data.id !== parseInt(userId)">
        {{store.state.user.data.id}} {{userId}}
        <PlanView v-if="store.state.stripe.clientSecret ===''"></PlanView>
        <CheckoutForm v-if="store.state.stripe.clientSecret !==''" />
    </div>

  <div class="flex items-center justify-between mb-3">


    <h1 class="text-3xl font-semibold">Contents</h1>
    <button type="button"
            @click="showAddNewModal"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new Content
    </button>
  </div>

  <ContentsTable/>
  <ContentModal v-model="showContentModal" :content="contentModel" @close="onModalClose"></ContentModal>


</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import ContentsTable from "./ContentsTable.vue";
import ContentModal from "./ContentModal.vue";
import PlanView from "../Subscribe/PlanView.vue";
import CheckoutForm from "../Subscribe/CheckoutForm.vue";
import {useRoute} from "vue-router";

const contents = computed(() => store.state.contents);
const showContentModal = ref(false);

const route = useRoute()
const userId = computed(() => route.params.id)

const DEFAULT_CONTENT = {
  id: '',
  title: '',
  description:''
}

const contentModel = ref({...DEFAULT_CONTENT})

function showAddNewModal() {
    showContentModal.value = true
}

function editContent(u) {
  contentModel.value = u;
  showAddNewModal();
}

function onModalClose() {
    contentModel.value = {...DEFAULT_CONTENT}
}

</script>

<style scoped>

</style>
