<template>
    <Crud
        :title="title"
        :title-pluralize="titlePluralize"
        :filter="filterConfig"
        :keys="tableKeys"
        :table="tableConfig"
        :actions="actionTypes"
        :url="url"
        :endpoint="endpoint"
        :session="session"
        :service="userService"
    >

        <template #name="{ value }">
            <div class="d-flex align-items-center">
                <div class="avatar-sm img-thumbnail rounded-circle flex-shrink-0">
                    <img
                        v-if="value.avatar"
                        :src="`${env.url}${value.avatar}`"
                        alt=""
                        class="member-img img-fluid d-block rounded-circle"
                    >
                    <div
                        v-else
                        class="avatar-title border bg-light text-primary rounded-circle text-uppercase"
                    >
                        {{ userService.generateNickname(value.name) }}
                    </div>
                </div>

                <div class="ms-2 name">
                    <div class="fw-bold">
                        {{ value.name }}
                    </div>
                    <div class="text-muted fs-12">
                        {{ value?.position?.name }}
                    </div>
                </div>
            </div>
        </template>

        <!-- Template para status do usuário -->
        <template #change_active="{ value }">
            <ChangeStatus
                :value="value"
                :endpoint="endpoint"
            />
        </template>

        <!-- Template para coluna telefone -->
        <template #phone="{ value }">
            <span>{{ value.phone ? maskPhone(value.phone) : '' }}</span>
        </template>

        <!-- Template para ações -->
        <template #actions="{ value }">
            <Actions
                :types="actionTypes"
                :value="value"
                :endpoint="endpoint"
                :session="session"
                @set-form-data="goToEdit"
            />
        </template>

    </Crud>
</template>

<script setup>
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import UserService from '@/services/UserService';

// Componentes
import Crud from "@/components/base/Crud.vue";
import {maskPhone} from "../../composables/masks.js";
import env from "@/env.js";
import ChangeStatus from "@/components/base/ChangeStatus.vue";
import Actions from "@/components/base/Actions.vue";

// Inicialização
const router = useRouter();
const userService = new UserService();
const session = 'Users';
const title = 'Usuário';
const titlePluralize = 'Usuários';
const endpoint = 'users';
const url = 'usuarios';

// Configurações obtidas do serviço
const filterConfig = ref(userService.getFilterConfig());
const tableConfig = userService.getTableConfig();
const tableKeys = userService.getTableKeys();
const actionTypes = userService.getActionTypes();

</script>

<style scoped>
.member-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.name {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>