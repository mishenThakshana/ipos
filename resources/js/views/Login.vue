<template>
    <div class="container">
        <div class="row flex justify-center items-center">
            <Container title="Login" icon="far fa-cube">
                <form @submit.prevent="loginUser">
                    <p
                        v-if="userState.errorMessage"
                        class="text-red-700 text-center"
                        v-text="String(userState.errorMessage)"
                    ></p>

                    <p
                        v-if="userState.returned_errors.email"
                        class="text-red-700 text-center"
                        v-text="String(userState.returned_errors.email)"
                    ></p>
                    <div class="flex justify-center items-center">
                        <input
                            v-model="userState.email"
                            class="bg-gray-800 text-gray-200 my-1 p-1 w-52 border border-1 border-indigo-500 outline-none"
                            type="email"
                            placeholder="Email"
                            autofocus
                        />
                    </div>

                    <p
                        v-if="userState.returned_errors.password"
                        class="text-red-700 text-center"
                        v-text="String(userState.returned_errors.password)"
                    ></p>
                    <div class="flex justify-center items-center">
                        <input
                            v-model="userState.password"
                            class="bg-gray-800 text-gray-200 my-1 p-1 w-52 border border-1 border-indigo-500 outline-none"
                            type="password"
                            placeholder="Password"
                        />
                    </div>
                    <div class="flex justify-center items-center">
                        <button
                            type="submit"
                            class="w-28 bg-green-600 p-2 shadow-2xl text-white hover:bg-green-500 transition-colors mt-2 rounded outline-none"
                        >
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                    <div
                        class="flex justify-center items-center mt-3 text-gray-300"
                    >
                        New to here
                        <RouterLink
                            class="underline mx-1 text-indigo-500"
                            to="/register"
                        >
                            Register now!</RouterLink
                        >
                    </div>
                </form>
            </Container>
        </div>
    </div>
</template>

<script setup>
import Container from "../components/Container.vue";
import { RouterLink } from "vue-router";
import useUserStore from "../stores/user";
import { reactive } from "vue";
import { useRouter } from "vue-router";

const routerHandler = useRouter();

const userStore = useUserStore();

const userState = reactive({
    email: "",
    password: "",
    returned_errors: [],
    errorMessage: "",
});

const loginUser = () => {
    axios
        .post("api/login", userState)
        .then((response) => {
            if (response.data.errorMessage) {
                userState.errorMessage = response.data.errorMessage;
            } else {
                userStore.userId = response.data.userId;
                localStorage.setItem("encryptedId", response.data.userId);
                userStore.userToken = response.data.token;
                localStorage.setItem("encryptedToken", response.data.token);
                routerHandler.push("/");
            }
        })
        .catch((error) => {
            userState.returned_errors = error.response.data.errors;
        });
};
</script>
