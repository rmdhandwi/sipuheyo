<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";

// Reaktivitas untuk tanggal dan waktu
const currentDate = ref("");
const currentTime = ref("");

const updateCurrentTime = () => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
    currentTime.value = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
};

onMounted(() => {
    updateCurrentTime();
    const interval = setInterval(updateCurrentTime, 1000);
    onUnmounted(() => {
        clearInterval(interval);
    });
});
</script>

<template>
    <div class="flex justify-between items-center text-lg text-gray-500 mb-2">
        <!-- Tanggal di sebelah kiri -->
        <span>Tanggal : {{ currentDate }}</span>

        <!-- Waktu di sebelah kanan -->
        <span>Jam : {{ currentTime }}</span>
    </div>
</template>

<style scoped>
/* Tambahkan gaya khusus jika diperlukan */
</style>
