<script setup>
import DokterLayout from "@/Layouts/DokterLayout.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import ArIcon from "@/dashboard/sidebar/icons/ArIcon.vue";
import DashboarItem from "@/Components/DashboarItem.vue";
import { Head } from "@inertiajs/vue3";

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
    <Head title="Dashboard" />
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
            <!-- <DashboarItem
                :count="props.resep"
                title="Jumlah Resep"
                bgicon="bg-red-500"
            >
                <CapsulesIcon color="white" class="w-10 h-10"></CapsulesIcon>
            </DashboarItem> -->
        </div>
    </DokterLayout>
</template>
