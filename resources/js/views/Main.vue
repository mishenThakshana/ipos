<template>
    <Navbar />
</template>

<script setup>
import useUserStore from "../stores/user";
import Navbar from "../components/Navbar.vue";

const userStore = useUserStore();
</script>
