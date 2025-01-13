<script setup>
import CapsulesIcon from "@/Icons/CapsulesIcon.vue";
import MedicalIcon from "@/Icons/MedicalIcon.vue";
import HospitalIcon from "@/Icons/HospitalIcon.vue";
import DoctorIcon from "@/Icons/DoctorIcon.vue";
import PatientIcon from "@/Icons/PatientIcon.vue";
import Layout from "@/dashboard/Layout.vue";
import UsersIcon from "@/Icons/UsersIcon.vue";
import ArIcon from "@/dashboard/sidebar/icons/ArIcon.vue"
import DashboarItem from "@/Components/DashboarItem.vue";
import { Head } from "@inertiajs/vue3";


const props = defineProps({
   obat: {
      type: String
   },
   poli: 0,
   dokter: 0,
   pasien: 0,
   pegawai: 0,
   rekammedik: 0,
})




</script>


<template>
   <Head title="Dashboard"/>
   <Layout>
      <div class="grid gap-2 lg:grid-cols-3 sm:grid-cols-2">
         <DashboarItem :count="props.obat" title="jenis obat" bgicon="bg-red-500">
            <CapsulesIcon color="white" class="w-10 h-10 "></CapsulesIcon>
         </DashboarItem>

         <DashboarItem :count="props.poli" title="jumlah poli" bgicon="bg-green-500">
            <HospitalIcon color="white" class="w-10 h-10 "></HospitalIcon>
         </DashboarItem>
         
         <DashboarItem :count="props.dokter" title="jumlah dokter" bgicon="bg-teal-500">
            <DoctorIcon color="white" class="w-10 h-10 "></DoctorIcon>
         </DashboarItem>
         <DashboarItem :count="props.pasien" title="jumlah pasien" bgicon="bg-amber-500">
            <PatientIcon color="white" class="w-10 h-10 "></PatientIcon>
         </DashboarItem>
         <DashboarItem :count="props.pegawai" title="jumlah pegawai" bgicon="bg-blue-500">
            <UsersIcon color="white" class="w-10 h-10 "></UsersIcon>
         </DashboarItem>
         <DashboarItem :count="props.rekammedik" title="rekam medik" bgicon="bg-violet-500">
            <ArIcon color="white" class="w-10 h-10 "></ArIcon>
         </DashboarItem>
      </div>
   </Layout>
</template>
