<template>
  <div>
    <p>Verificando suas credenciais...</p>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useAuthStore } from "@/stores/auth.js";
import { useRouter, useRoute } from "vue-router";
import { notifyError } from "@/composables/messages";
import http from "@/http";
import env from "@/env";

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const verifyCredentials = async () => {
    const token = route.query.token;

    // Verifica se o token existe
    if (token) {
        try {
            localStorage.setItem("token", token);
            
            // Buscar dados do usuário usando o token
            const response = await http.get(env.api + 'me');
            
            // Salvar usuário no store do Pinia
            authStore.setCurrentUser(response.data.message.data);
            
            // Salvar permissões se disponíveis
            if (response.data.message.data.permissions) {
                localStorage.setItem('permissions', JSON.stringify(response.data.message.data.permissions));
            }
            
            // Atualizar token se um novo foi enviado
            if (response.data.message.token) {
                localStorage.setItem('token', response.data.message.token);
            }
            
            // Carregar enums
            try {
                const enumsResponse = await http.get(env.api + 'enums');
                authStore.setEnums(enumsResponse.data.data);
            } catch (error) {
                console.warn('Não foi possível carregar enums:', error);
            }
            
            // Redirecionar para o dashboard
            router.push('/');
        } catch (e) {
            console.error("Erro ao verificar credenciais: ", e);
            notifyError("Erro ao logar com o Google!");
            
            // Limpar dados em caso de erro
            localStorage.removeItem("token");
            localStorage.removeItem("permissions");
            authStore.setCurrentUser(null);
            
            await router.push("/login");
        }
    } else {
        // Redireciona para o login se não tiver token
        await router.push("/login");
    }
};

// Quando o componente for montado
onMounted(() => {
    verifyCredentials();
});
</script>

