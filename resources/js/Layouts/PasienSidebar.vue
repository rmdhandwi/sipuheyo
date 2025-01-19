<script setup lang="ts">
import SidebarItemSection from "@/dashboard/sidebar/SidebarItemSection.vue";
import SidebarItem from "@/dashboard/sidebar/SidebarItem.vue";
import AllAppIcon from "@/dashboard/sidebar/icons/AllAppIcon.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import SidebarHeader from "@/dashboard/sidebar/SidebarHeader.vue";
import { usePage } from "@inertiajs/vue3";

const prop = defineProps({
    pasien: {
        type: Object,
    },
});

const emit = defineEmits(["titleChange"]);

const page = usePage();


</script>

<template>
    <div>
        <SidebarHeader></SidebarHeader>
        <SidebarItemSection name="PASIEN">
            <SidebarItem
                title="Rekam Medik"
                :href="route('pasien.index')"
                :active="
                    page.url === '/pasien' ||
                    page.url.startsWith('/pasien/rekammedik/detail')
                "
            >
                <AllAppIcon class="text-black" />
            </SidebarItem>
            <SidebarItem
                title="Daftar"
                :href="'/pasien/rekammedik/add'"
                :active="page.url.startsWith('/pasien/rekammedik/add')"
            >
                <PatientIcon class="w-5 h-5 text-slate-200" />
            </SidebarItem>
        </SidebarItemSection>
    </div>
</template>
