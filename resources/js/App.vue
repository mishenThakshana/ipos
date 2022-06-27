<template>
    <RouterView />
</template>

<script setup>
import { RouterView } from "vue-router";
import { useRouter } from "vue-router";
import useUserStore from "./stores/user";

const routerHandler = useRouter();
const userStore = useUserStore();

userStore.userToken ? false : routerHandler.push("/login");
</script>

<style lang="scss" scoped></style>
