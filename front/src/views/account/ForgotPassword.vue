<script>
import Lottie from "@/components/widgets/lottie.vue";
import animationData from "@/components/widgets/rhvddzym.json";
import http from "@/http";
import {notifyError, notifySuccess} from "@/composables/messages";

export default {
    components: {lottie: Lottie},
    page: {
        title: "Recuperar Senha",
        meta: [
            {
                name: "description",
            },
        ],
    },
    data() {
        return {
            email: "",
            submitted: false,
            error: null,
            tryingToReset: false,
            defaultOptions: {animationData: animationData},
            load: false
        };
    },
    methods: {
        signinapi() {
            this.load = true;
            if (this.email === '') {
                notifyError('Informe um e-mail!');
                setTimeout(() => {
                    this.load = false;
                }, 200)
                return;
            }
            http.post('recuperar', {
                email: this.email,
            })
                .then((response) => {
                    notifySuccess(response.data.message)
                })
                .catch((response) => {
                    notifyError(response.response.data)
                })
                .finally(() => {
                    setTimeout(() => {
                        this.load = false;
                    }, 200)
                })
        }
    },
};
</script>

<!--eslint-disable no-mixed-spaces-and-tabs-->
<template>
    <div class="auth-page-wrapper pt-5">
        <div
            id="auth-particles"
            class="auth-one-bg-position auth-one-bg bg-soft-primary"
        />

        <div class="auth-page-content">
            <b-container>
                <b-row/>

                <b-row class="justify-content-center">
                    <b-col
                        md="8"
                        lg="6"
                        xl="5"
                    >
                        <b-card no-body>
                            <b-card-body class="p-4">
                                <div class="text-center mt-2">
                                    <img
                                        src="@/assets/logos/logo-grande.png"
                                        alt="logo_bauminas"
                                        height="80"
                                    >
                                    <h5 class="text-primary mt-3">
                                        Esqueceu a Senha?
                                    </h5>
                                    <p class="text-muted">
                                        Redefinir senha
                                    </p>

                                    <lottie
                                        class="avatar-xl"
                                        colors="primary:#3cd188,secondary:#687cfe"
                                        :options="defaultOptions"
                                        :height="120"
                                        :width="120"
                                    />
                                </div>

                                <div class="p-2">
                                    <form @submit.prevent="signinapi">
                                        <div class="mb-4">
                                            <label class="form-label">Email</label>
                                            <input
                                                id="email"
                                                v-model="email"
                                                type="email"
                                                class="form-control"
                                                placeholder="Digite seu e-mail"
                                            >
                                        </div>

                                        <div class="text-center mt-4">
                                            <b-button
                                                variant="success"
                                                class="w-100"
                                                type="submit"
                                            >
                                                <span class="d-flex align-items-center margin-load">
                                                    <span class="flex-grow-1 m-r-10">
                                                        Enviar link de redefinição
                                                    </span>
                                                    <span
                                                        :class="{ invisible: !load }"
                                                        class="spinner-border flex-shrink-0"
                                                        role="status"
                                                    >
                                                        <span class="visually-hidden"/>
                                                    </span>
                                                </span>
                                            </b-button>
                                        </div>
                                    </form>
                                </div>
                            </b-card-body>
                        </b-card>

                        <div class="mt-4 text-center">
                            <p class="mb-0">
                                Eu lembro da minha senha...
                                <router-link
                                    to="/login"
                                    class="fw-semibold text-primary text-decoration-underline"
                                >
                                    Clique aqui
                                </router-link>
                            </p>
                        </div>
                    </b-col>
                </b-row>
            </b-container>
        </div>

        <footer class="footer">
            <b-container>
                <b-row>
                    <b-col lg="12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">
                                &copy; {{ new Date().getFullYear() }}
                                <a
                                    target="_blank"
                                    class="text-primary"
                                    href="https://lifecode.dev/"
                                >LifeCode.
                                </a>
                                Feito com o
                                <i class="mdi mdi-heart text-danger"/>
                            </p>
                        </div>
                    </b-col>
                </b-row>
            </b-container>
        </footer>
    </div>
</template>

<style>

#auth-particles {
    height: 100%;
}


</style>
