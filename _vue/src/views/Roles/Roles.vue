<template>
  <div class="m-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-3xl font-semibold">Roles</h1>
      <button type="button"
              @click="showAddNewModal()"
              class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Add new Role
      </button>
    </div>
    <RolesTable @clickEdit="editRole"/>
    <RoleModal v-model="showRoleModal" :role="roleModel" @close="onModalClose"/>
  </div>

</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import RoleModal from "./RoleModal.vue";
import RolesTable from "./RolesTable.vue";

const DEFAULT_ROLE = {
    id: '',
    name: '',
    guard_name: '',
    permissions:[]
}

const roles = computed(() => store.state.roles);

const roleModel = ref({...DEFAULT_ROLE})
const showRoleModal = ref(false);

function showAddNewModal() {
  showRoleModal.value = true
}

function editRole(u) {
    roleModel.value = u;
    showAddNewModal();
}

function onModalClose() {
  roleModel.value = {...DEFAULT_ROLE}
}

</script>

<style scoped>

</style>
