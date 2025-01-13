<script setup lang="ts">

const prop = defineProps({
  poli: {
    type: Poli,
    default: ''
  }
});

const emit = defineEmits(['titleChange']);

import SidebarItemSection from '@/dashboard/sidebar/SidebarItemSection.vue';
import SidebarItem from '@/dashboard/sidebar/SidebarItem.vue';
import AllAppIcon from '@/dashboard/sidebar/icons/AllAppIcon.vue';
import UpdatesIcon from '@/dashboard/sidebar/icons/UpdatesIcon.vue';
import PatientIcon from '@/Icons/PatientIcon.vue';
import MedicalIcon from '@/Icons/MedicalIcon.vue';
import Poli from '@/Models/Poli';
import { onMounted } from 'vue';
import SidebarHeader from '@/dashboard/sidebar/SidebarHeader.vue';
import DetailListIcon from '@/Icons/DetailListIcon.vue';

onMounted(() => {
    emit('titleChange', prop.poli.nama);
})


</script>
<template>
  <div>
 <SidebarHeader></SidebarHeader>
    <SidebarItemSection name="APP DOKTER">
      <SidebarItem title="Dashboard" to="/dokter">
        <AllAppIcon class="text-black" />
      </SidebarItem>
      <SidebarItem title="Pasien" to="/dokter/pasien">
        <PatientIcon class="w-5 h-5" />
      </SidebarItem>
      <SidebarItem title="Rekam Medik" to="/dokter/rekammedik">
        <MedicalIcon class="w-5 h-5" />
      </SidebarItem>
      <SidebarItem title="Laporan Jadwal Berobat" to="/dokter/jadwalberobat">
        <DetailListIcon class="w-5 text-black" />
      </SidebarItem>
    </SidebarItemSection>
  </div>
</template>