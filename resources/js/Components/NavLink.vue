<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});

const classes = computed(() =>
    props.active
        ? "inline-flex items-center px-1 pt-1 bg-white text-sm font-bold leading-5 text-white focus:outline-none transition duration-150 ease-in-out"
        : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:bg-white focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out"
);

</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
