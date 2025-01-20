<script setup lang="ts">
import SidebarItemSection from "@/dashboard/sidebar/SidebarItemSection.vue";
import SidebarItem from "@/dashboard/sidebar/SidebarItem.vue";
import AllAppIcon from "@/dashboard/sidebar/icons/AllAppIcon.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import MedicalIcon from "@/Icons/MedicalIcon.vue";
import SidebarHeader from "@/dashboard/sidebar/SidebarHeader.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";
import { usePage } from "@inertiajs/vue3";
import Dokter from "@/Models/Dokter";

const prop = defineProps({
    poli: {
        type: Array,
    },
    dokter: {
        type: Array,
    },
});

// Mengambil informasi halaman saat ini
const page = usePage();
</script>
<template>
    <div>
        <SidebarHeader></SidebarHeader>
        <SidebarItemSection name="APP DOKTER" :subname="prop.poli.nama">
            <SidebarItem
                title="Dashboard"
                :href="'/dokter'"
                :active="page.url === '/dokter'"
            >
                <AllAppIcon class="text-black" />
            </SidebarItem>

            <SidebarItem
                title="Rekam Medik"
                :href="'/dokter/rekammedik'"
                :active="page.url.startsWith('/dokter/rekammedik')"
            >
                <MedicalIcon class="w-5 h-5" />
            </SidebarItem>
            <SidebarItem
                title="Laporan Jadwal Berobat"
                :href="'/dokter/jadwalberobat'"
                :active="page.url.startsWith('/dokter/jadwalberobat')"
            >
                <DetailListIcon class="w-5 text-black" />
            </SidebarItem>
        </SidebarItemSection>
    </div>
</template>
