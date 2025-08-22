<template>
  <div class="mb-4 card-border">
    <BCardHeader>
      <h5 class="mb-0">
        Permissões de Acesso
      </h5>
    </BCardHeader>
    <BCardBody>
      <BRow>
        <BCol xxl="12">
          <Permissions
            :model-value="formData"
            :permissions="permissionOptions"
            @update:model-value="updatePermissions"
          />
          <BFormInvalidFeedback
            v-if="getFieldError('permissions')"
            id="permissions-error-feedback"
            class="d-block mt-2"
          >
            {{ getFieldError('permissions') }}
          </BFormInvalidFeedback>
        </BCol>
      </BRow>
    </BCardBody>
  </div>
</template>

<script setup>
import {defineProps, defineEmits} from 'vue';

import Permissions from "@/views/users/Permissions.vue";

const props = defineProps({
    formData: {
        type: Array,
        default: () => []
    },
    permissionOptions: {
        type: Array,
        default: () => []
    },
    errors: {
        type: Object,
        default: () => ({})
    }
});

const emit = defineEmits(['update:formData']);

// Helper functions for validation styling
function getFieldError(fieldName) {
    return props.errors[fieldName]?.[0] || null;
}

// Função para atualizar permissões
function updatePermissions(newPermissions) {
    emit('update:formData', newPermissions);
}
</script>