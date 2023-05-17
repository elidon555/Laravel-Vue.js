<template>
  <div class="m-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Permissions</h1>
      <button type="button"
              @click="showAddNewModal()"
              class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add new Permission
      </button>
    </div>
    <PermissionsTable @clickEdit="editPermission"/>
    <PermissionModal v-model="showPermissionModal" :permission="permissionModel" @close="onModalClose"/>
  </div>

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import PermissionModal from "./PermissionModal.vue";
import PermissionsTable from "./PermissionsTable.vue";

const DEFAULT_PERMISSION = {
  id: '',
  name: '',
  guard_name:''
}

const permissions = computed(() => store.state.permissions);

const permissionModel = ref({...DEFAULT_PERMISSION})
const showPermissionModal = ref(false);

function showAddNewModal() {
  showPermissionModal.value = true
}

function editPermission(u) {
    permissionModel.value = u;
    showAddNewModal();
}

function onModalClose() {
  permissionModel.value = {...DEFAULT_PERMISSION}
}

</script>

<style scoped>

</style>
