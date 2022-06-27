<template>
    <div class="container-fluid">
        <div
            class="row flex justify-center items-center h-16 bg-gray-800 border-b-4 border-indigo-500"
        >
            <div
                class="flex justify-center items-center text-center text-gray-200 text-md"
            >
                <RouterLink class="mx-3" to="/">
                    <i class="fas fa-home"></i>
                    Home
                </RouterLink>
                <RouterLink class="mx-3" to="/management">
                    <i class="fas fa-sliders-h"></i>
                    Management
                </RouterLink>
                <button @click="logoutUser" class="mx-3">
                    <i class="fas fa-sign-in-alt"></i>
                    Logout
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from "axios";
import { RouterLink } from "vue-router";
import { useRouter } from "vue-router";
import useUserStore from "../stores/user";

const userStore = useUserStore();

const routerHandler = useRouter();

//config axios header token
const config = {
    headers: { Authorization: `Bearer ${userStore.userToken}` },
};

const logoutUser = () => {
    axios.post("api/logout", { data: "" }, config).then(() => {
        userStore.userId = null;
        localStorage.removeItem("encryptedId");
        userStore.userToken = null;
        localStorage.removeItem("encryptedToken");
        routerHandler.push("/login");
    });
};
</script>

<style lang="scss" scoped></style>
