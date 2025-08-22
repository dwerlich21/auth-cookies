<script setup>
import {ref, reactive, defineProps} from "vue";
import http from "@/http";
import {notifyError, notifySuccess} from "@/composables/messages";
import {encodeId} from "@/composables/functions.js";

// Props recebidas
const props = defineProps({
    value: Object,
    endpoint: String,
});

// Controle de carregamento e cópia reativa
const load = ref(true);
const key = ref(0);
const copy = reactive({status: props.value.active});

const changeStatus = () => {
    if (load.value) {
        load.value = false;

        http
            .put(`${props.endpoint}/change-active/${encodeId(props.value.id)}`)
            .then(() => {
                copy.status = !copy.status;
                notifySuccess(
                    `Status alterado para ${copy.active ? "Ativo" : "Inativo"}`
                );
                ++key.value;
            })
            .catch((errors) => {
                console.error(errors);
                notifyError(errors.response);
            })
            .finally(() => {
                setTimeout(() => {
                    load.value = true;
                }, 200);
            });
    }
};
</script>

<template>
    <span
        :key="key"
        class="pointer fs-14 badge"
        :class="!!copy.status ? 'bg-success-subtle' : 'bg-danger-subtle'"
        @click.prevent="changeStatus"
    >
        <span v-if="load">{{ copy.status ? 'Ativo' : 'Inativo' }}</span>
        <BSpinner
            v-else
            style="height: 1rem; width: 1rem;"
        />
    </span>
</template>
