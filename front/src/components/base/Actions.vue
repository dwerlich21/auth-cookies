<script setup>
import {showAlertConfirm, Forbidden, encodeId} from "@/composables/functions";
import {defineProps, defineEmits} from "vue";
import http from "@/http";
import {endLoading, startLoading} from "@/composables/spinners";

const props = defineProps({
    types: {
        type: Array,
        required: true
    },
    value: {
        type: Object,
        required: true
    },
    endpoint: {
        type: String,
        required: true
    },
    session: {
        type: String,
        required: true
    },

})

const emits = defineEmits(['set-form-data']);

const getView = (id) => {
    startLoading('form', 'save');

    http.get(`${props.endpoint}/${id}`)
        .then(response => {
            emits('set-form-data', response.data.message)
        })
        .catch(errors => {
            console.error(errors);
            endLoading('form', 'save');
            Forbidden(errors);
        });
}

// Exclusão de item da tabela
const delElement = async () => {
    const result = await showAlertConfirm('Seus dados serão removidos e não poderão mais ser recuperados.');

    if (result) {
        const data = {
            id: props.value.id,
            session: props.session
        }
        return apiStore.delete(data);
    }
}

</script>

<template>
    <span>
        <slot name="add-icons"/>

        <i
            v-if="props.types.indexOf('modal') > -1"
            v-b-tooltip.hover="'Editar Registro'"
            class="bx bx-pencil text-info fs-14 mx-1 pointer"
            @click="getView(props.value.id)"
        />
        <router-link
            :to="{ name: `${endpoint.replace('/','')}-form` , params: { id: encodeId(props.value.id) }}"
        >
            <i
                v-if="props.types.indexOf('page') > -1"
                v-b-tooltip.hover="'Editar Registro'"
                class="bx bx-pencil text-info fs-14 mx-1 pointer"
            />
        </router-link>
        <i
            v-if="props.types.indexOf('delete') > -1"
            v-b-tooltip.hover="'Excluir Registro'"
            class="bx bx-trash text-danger fs-14 mx-1 pointer"
            @click="delElement"
        />
    </span>
</template>
