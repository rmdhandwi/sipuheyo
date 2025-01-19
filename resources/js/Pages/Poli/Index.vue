<script setup>
import CapsulesIcon from "@/Icons/CapsulesIcon.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import ArIcon from "@/dashboard/sidebar/icons/ArIcon.vue";
import DashboarItem from "@/Components/DashboarItem.vue";
import PoliLayout from "@/Layouts/PoliLayout.vue";

const props = defineProps({
    resep: {
        type: String,
    },
    poli: {
        type: Array,
    },
    pegawai: {
        type: Array,
    },
    pasienCount: 0,
    rekammedikCount: 0,
});
</script>

<template>
    <PoliLayout :poli="props.poli" :pegawai="props.pegawai">
        <div class="grid gap-2 lg:grid-cols-3 sm:grid-cols-2">
            <!-- <DashboarItem
                :count="props.pasienCount"
                title="jumlah pasien"
                bgicon="bg-amber-500"
            >
                <PatientIcon color="white" class="w-10 h-10"></PatientIcon>
            </DashboarItem> -->
            <DashboarItem
                :count="props.rekammedikCount"
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
    </PoliLayout>
</template>
