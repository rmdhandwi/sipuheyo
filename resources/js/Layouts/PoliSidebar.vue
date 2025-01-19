<script setup lang="ts">
const data = reactive({ poli: {} as Poli });

const emit = defineEmits(["titleChange"]);

import SidebarItemSection from "@/dashboard/sidebar/SidebarItemSection.vue";
import SidebarItem from "@/dashboard/sidebar/SidebarItem.vue";
import AllAppIcon from "@/dashboard/sidebar/icons/AllAppIcon.vue";
import MedicalIcon from "@/Icons/MedicalIcon.vue";
import Poli from "@/Models/Poli";
import { usePage } from "@inertiajs/vue3";
import SidebarHeader from "@/dashboard/sidebar/SidebarHeader.vue";
import { reactive } from "vue";
import RealtimeClock from "@/Components/RealtimeClock.vue";

const page = usePage();

const props = defineProps({
    poli: {
        type: Array,
    },
    pegawai: {
        type: Array,
    },
});
</script>
<template>
    <div>
        <SidebarHeader></SidebarHeader>
        <SidebarItemSection name="APP POLI" :subname="props.poli.nama">

            <SidebarItem
                title="Dashboard"
                :href="'/poli'"
                :active="page.url === '/poli'"
            >
                <AllAppIcon class="text-black" />
            </SidebarItem>
            <SidebarItem
                title="Rekam Medik"
                :href="'/poli/rekammedik'"
                :active="page.url.startsWith('/poli/rekammedik')"
            >
                <MedicalIcon class="w-5 h-5" />
            </SidebarItem>
        </SidebarItemSection>
    </div>
</template>
