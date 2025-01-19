<script setup>
import DokterLayout from "@/Layouts/DokterLayout.vue";
import Poli from "@/Models/Poli";
import CapsulesIcon from "@/Icons/CapsulesIcon.vue";
import MedicalIcon from "@/Icons/MedicalIcon.vue";
import HospitalIcon from "@/Icons/HospitalIcon.vue";
import DoctorIcon from "@/Icons/DoctorIcon.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import Layout from "@/dashboard/Layout.vue";
import UsersIcon from "@/Icons/UsersIcon.vue";
import ArIcon from "@/dashboard/sidebar/icons/ArIcon.vue";
import DashboarItem from "@/Components/DashboarItem.vue";

const props = defineProps({
    resep: {
        type: String,
    },
    poli: {
        type: String,
    },
    dokter: {
        type: String,
    },
    pasien: 0,
    rekammedik: 0,
});
</script>

<template>
    <DokterLayout :poli="props.poli" :dokter="props.dokter">
        <div class="grid gap-2 lg:grid-cols-3 sm:grid-cols-2">
            <DashboarItem
                :count="props.pasien"
                title="jumlah pasien"
                bgicon="bg-amber-500"
            >
                <PatientIcon color="white" class="w-10 h-10"></PatientIcon>
            </DashboarItem>
            <DashboarItem
                :count="props.rekammedik"
                title="jumlah rekam medik"
                bgicon="bg-violet-500"
            >
                <ArIcon color="white" class="w-10 h-10"></ArIcon>
            </DashboarItem>
            <DashboarItem
                :count="props.resep"
                title="Jumlah Resep"
                bgicon="bg-red-500"
            >
                <CapsulesIcon color="white" class="w-10 h-10"></CapsulesIcon>
            </DashboarItem>
        </div>
    </DokterLayout>
</template>
