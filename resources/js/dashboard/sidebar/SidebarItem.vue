<script setup lang="ts">
import NavLink from "@/Components/NavLink.vue";
import { useRoute } from "vue-router";

defineProps({
  to: {
    type: String,
    default: "",
  },
  title: {
    type: String,
    default: "",
  },
});
</script>

<template>

  <NavLink :href="to">
    <span class="my-3 flex w-full items-center justify-start">
      <span class=" w-6" >
        <slot />
      </span>
      <span class="mx-4 text-sm text-black">
        {{ title }}
      </span>
    </span>
  </NavLink>
</template>
