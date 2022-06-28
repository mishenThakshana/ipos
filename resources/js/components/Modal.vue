<template>
    <div class="flex h-screen items-start justify-center fixed z-10">
        <div class="z-1 h-1/4 w-1/3 rounded bg-gray-800 shadow-2xl mt-32">
            <div class="container h-full">
                <div
                    class="row h-1/2 items-center justify-center pt-12 text-white"
                >
                    <h1 class="text-center text-xl">{{ props.title }}</h1>
                    <div class="text-center">
                        <slot> </slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
});
</script>
