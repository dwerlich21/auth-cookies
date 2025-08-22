<script>
import {notifyError, notifySuccess} from "@/composables/messages";
import http from "@/http";

export default {
    data() {
        return {
            load: false
        }
    },
    mounted() {
        document.querySelectorAll("form .auth-pass-inputgroup")
            .forEach(function (item) {
                item.querySelectorAll(".password-addon").forEach(function (subitem) {
                    subitem.addEventListener("click", function () {
                        var passwordInput = item.querySelector(".password-input");
                        if (passwordInput.type === "password") {
                            passwordInput.type = "text";
                        } else {
                            passwordInput.type = "password";
                        }
                    });
                });
            });


        var myInput = document.getElementById("password-input");
        var letter = document.getElementById("pass-lower");
        var capital = document.getElementById("pass-upper");
        var number = document.getElementById("pass-number");
        var length = document.getElementById("pass-length");

        // When the user starts to type something inside the password field
        myInput.onkeyup = function () {
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Validate length
            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        };
    },
    methods: {
        validatePassword() {
            this.load = true;
            var passwordContainer = document.getElementById("password-contain");
            var invalidElement = passwordContainer.querySelector(".invalid");
            if (invalidElement) {
                notifyError('Preencha todos os requisitos');
                setTimeout(() => {
                    this.load = false;
                }, 200)
                return;
            }

            var password = document.getElementById("password-input"),
                password2 = document.getElementById("confirm-password-input");
            if (password.value !== password2.value) {
                notifyError('As senhas não são iguais!');
                setTimeout(() => {
                    this.load = false;
                }, 200)
                return;
            }
            http.put('recuperar', {
                password: password.value,
                password2: password2.value,
                'token': this.$route.params.token
            })
                .then((response) => {
                    notifySuccess(response.data.message);
                    setTimeout(() => {
                        this.$router.push('/login')
                    }, 300)
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
                                <div class="mt-2">
                                    <div class="text-center">
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
                                    </div>

                                    <div class="p-2">
                                        <form>
                                            <div class="mb-3">
                                                <label
                                                    class="form-label text-start"
                                                    for="password-input"
                                                >Senha
                                                </label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input
                                                        id="password-input"
                                                        type="password"
                                                        class="form-control pe-5 password-input"
                                                        onpaste="return false"
                                                        placeholder="Senha"
                                                        aria-describedby="passwordInput"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                        required
                                                    >
                                                    <BButton
                                                        id="password-addon"
                                                        variant="link"
                                                        class="position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    >
                                                        <i
                                                            class="ri-eye-fill align-middle"
                                                        />
                                                    </BButton>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label
                                                    class="form-label"
                                                    for="confirm-password-input"
                                                >Confirmar
                                                 Senha
                                                </label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input
                                                        id="confirm-password-input"
                                                        type="password"
                                                        class="form-control pe-5 password-input"
                                                        onpaste="return false"
                                                        placeholder="Confirmar Senha"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                                        required
                                                    >
                                                    <BButton
                                                        id="confirm-password-input1"
                                                        variant="link"
                                                        class="position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                        type="button"
                                                    >
                                                        <i
                                                            class="ri-eye-fill align-middle"
                                                        />
                                                    </BButton>
                                                </div>
                                            </div>

                                            <div
                                                id="password-contain"
                                                class="p-3 bg-light mb-2 rounded text-center"
                                            >
                                                <h5 class="fs-13">
                                                    A senha deve conter:
                                                </h5>
                                                <p
                                                    id="pass-length"
                                                    class="invalid fs-12 mb-2"
                                                >
                                                    No mínimo <b>8 caracteres</b>
                                                </p>
                                                <p
                                                    id="pass-lower"
                                                    class="invalid fs-12 mb-2"
                                                >
                                                    Pelo menos uma letra <b>minúscula</b>
                                                </p>
                                                <p
                                                    id="pass-upper"
                                                    class="invalid fs-12 mb-2"
                                                >
                                                    Pelo manos uma letra <b>Maiúscula</b>
                                                </p>
                                                <p
                                                    id="pass-number"
                                                    class="invalid fs-12 mb-0"
                                                >
                                                    Pelo menos um <b>Número</b>
                                                </p>
                                            </div>

                                            <div class="mt-4">
                                                <b-button
                                                    variant="success"
                                                    class="w-100 btn-load"
                                                    @click="validatePassword"
                                                >
                                                    <span class="d-flex align-items-center margin-load">
                                                        <span v-if="!load">
                                                            Atualizar Senha
                                                        </span>
                                                        <span
                                                            v-else
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
                                </div>
                            </b-card-body>
                        </b-card>

                        <div class="mt-4 text-center">
                            <p class="mb-0">
                                Lembro da minha senha...
                                <router-link
                                    to="/login"
                                    class="fw-semibold text-primary text-decoration-underline"
                                >
                                    Login
                                </router-link>
                            </p>
                        </div>
                    </b-col>
                </b-row>
            </b-container>
        </div>

        <footer class="footer">
            <BContainer>
                <BRow>
                    <BCol lg="12">
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
                    </BCol>
                </BRow>
            </BContainer>
        </footer>
    </div>
</template>

<style>

#auth-particles {
    height: 100%;
}


</style>
