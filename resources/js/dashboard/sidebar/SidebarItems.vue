<script setup lang="ts">
import ArIcon from "./icons/ArIcon.vue";
import SidebarItem from "./SidebarItem.vue";
import AllAppIcon from "./icons/AllAppIcon.vue";
import SidebarItemSection from "./SidebarItemSection.vue";
import DoctorIcon from "@/Icons/DoctorIcon.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";
import CapsulesIcon from "@/Icons/CapsulesIcon.vue";
import HospitalIcon from "@/Icons/HospitalIcon.vue";
import UsersIcon from "@/Icons/UsersIcon.vue";
import { usePage } from "@inertiajs/vue3";
import RealtimeClock from "@/Components/RealtimeClock.vue";

// Mengambil informasi halaman saat ini
const page = usePage();
</script>

<template>
    <div>
        <SidebarItemSection
            name="APP ADMIN"
            :subname="$page.props.auth.user.name"
        >
            <SidebarItem
                title="Dashboard"
                :href="'/admin'"
                :active="page.url === '/admin'"
            >
                <AllAppIcon color="black" />
            </SidebarItem>

            <SidebarItem
                title="Obat"
                :href="'/admin/obat'"
                :active="page.url.startsWith('/admin/obat')"
            >
                <CapsulesIcon color="black" class="w-5 h-5" />
            </SidebarItem>

            <SidebarItem
                title="Pegawai"
                :href="'/admin/pegawai'"
                :active="page.url.startsWith('/admin/pegawai')"
            >
                <UsersIcon color="black" class="w-5 h-5" />
            </SidebarItem>

            <SidebarItem
                title="Dokter"
                :href="'/admin/dokter'"
                :active="page.url.startsWith('/admin/dokter')"
            >
                <DoctorIcon color="black" class="w-5 h-auto" />
            </SidebarItem>

            <SidebarItem
                title="Poli"
                :href="'/admin/poli'"
                :active="page.url.startsWith('/admin/poli')"
            >
                <HospitalIcon color="black" class="w-5 h-5" />
            </SidebarItem>

            <SidebarItem
                title="Pasien"
                :href="'/admin/pasien'"
                :active="page.url.startsWith('/admin/pasien')"
            >
                <PatientIcon color="black" class="w-5 h-auto" />
            </SidebarItem>

            <SidebarItem
                title="Rekam Medik"
                :href="'/admin/rekammedik'"
                :active="page.url.startsWith('/admin/rekammedik')"
            >
                <ArIcon />
            </SidebarItem>

            <SidebarItem
                title="Laporan Jadwal Berobat"
                :href="'/admin/jadwalberobat'"
                :active="page.url.startsWith('/admin/jadwalberobat')"
            >
                <DetailListIcon color="black" class="w-5" />
            </SidebarItem>
        </SidebarItemSection>
    </div>
</template>
