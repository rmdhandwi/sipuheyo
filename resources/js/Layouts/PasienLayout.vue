<script setup lang="ts">
import { onMounted, watch } from "vue";
import TopBar from "@/dashboard/TopBar.vue";
import Overlay from "@/dashboard/Overlay.vue";
import DokterSidebar from "./DokterSidebar.vue";
import { closeSidebar, sidebarOpen } from "@/dashboard/store";
import Pasien from "@/Models/Pasien";
import PasienSidebar from "./PasienSidebar.vue";
import RealtimeClock from "@/Components/RealtimeClock.vue";

const props = defineProps({
    pasien: { type: Object },
});
  
onMounted(() => {
    // set the html tag attribute overflow to hidden when component is mounted
    document.documentElement.style.overflow = "hidden";
});

watch(route, () => {
    // close sidebar on route changes when viewport is less than 1024px
    if (sidebarOpen && window.innerWidth < 1024) {
        closeSidebar();
    }
});
</script>

<!-- lg:w-[calc(100%-16rem)] class get the remained width of the main tag from lg:viewport by subtracting
(the total width by the width of the sidebar component which is w-64 = 16rem)-->
<template>
    <div class="background h-screen w-full overflow-hidden lg:p-4">
        <div class="content relative h-screen overflow-hidden lg:rounded-2xl">
            <div class="flex items-start">
                <Overlay />
                <PasienSidebar :pasien="props.pasien" mobile-orientation="end" />
                <div
                    class="flex h-screen w-full flex-col pl-0 lg:w-[calc(100%-16rem)] lg:space-y-4"
                >
                    <TopBar />
                    <main
                        class="main h-screen px-2 pb-36 pt-4 md:px-4 md:pb-8 lg:px-6"
                    >
                    <RealtimeClock/>
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.background {
    background-image: url("../../images/background.jpg");
    background-position: center;
    background-size: cover;
}

.content {
    background-color: rgba(49, 49, 49, 0.1);
    backdrop-filter: blur(5px);
}

.main {
    color: black;
    background-color: rgba(255, 255, 255, 1);
    overflow: auto;
}
.main::-webkit-scrollbar {
    width: 6px;
    border-radius: 10px;
}

.main::-webkit-scrollbar-thumb {
    background: rgb(1 2 3 / 40%);
    border-radius: 10px;
}
</style>
