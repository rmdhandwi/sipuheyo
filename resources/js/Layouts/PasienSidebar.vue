<script setup lang="ts">

const prop = defineProps({
  pasien: {
    type: Pasien,
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
import Pasien from '@/Models/Pasien';
import SidebarHeader from '@/dashboard/sidebar/SidebarHeader.vue';

onMounted(() => {
    emit('titleChange', prop.pasien.nama);
})


</script>
<template>
  <div >
    <SidebarHeader></SidebarHeader>
    <SidebarItemSection name="PASIEN" :subname="prop.pasien.nama">
      <SidebarItem title="Rekam Medik" to="/pasien">
        <AllAppIcon class=" text-black" />
      </SidebarItem>
      <SidebarItem title="Daftar" to="/pasien/rekammedik/add">
        <PatientIcon class="w-5 h-5  text-slate-200" />
      </SidebarItem>
    </SidebarItemSection>
  </div>
</template>