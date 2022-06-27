<template>
    <div
        :class="[
            'col-lg-4',
            // storeProducts.isBackgroundBlurred ? 'blur-sm' : '',
        ]"
    >
        <div class="w-full bg-gray-800 mt-4 mb-4 rounded shadow-2xl pt-2">
            <div
                class="w-full h-8 text-center border-b-4 border-indigo-500 text-white text-lg pb-9 font-bold"
            >
                <i :class="props.icon"></i> {{ props.title }}
            </div>
            <div class="container p-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
    icon: String,
});
</script>

<style scoped></style>
