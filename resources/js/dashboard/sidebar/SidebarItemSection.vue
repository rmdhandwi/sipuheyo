<script setup lang="ts">
defineProps({
  name: {
    type: String,
    default: "",
  },
  subname: {
  },
});
</script>

<template>
  <div class="mt-10 md:pl-6">
    <div>
      <div class="mb-10 flex flex-col">
        <div class="mb-4 text-lg font-medium text-wrap">
          {{ name }}
        </div>
        <div class="mb-4 text-lg font-medium ">
          {{ subname }}
        </div>
        <slot />
      </div>
    </div>
  </div>
</template>
