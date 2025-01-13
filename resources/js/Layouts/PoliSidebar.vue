<script setup lang="ts">

const data=reactive({poli:{} as Poli});

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
import Helper from '@/heper';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3'
import SidebarHeader from '@/dashboard/sidebar/SidebarHeader.vue';
import { reactive } from 'vue';

const page = usePage()

onMounted(() => {
    axios
        .get(Helper.apiUrl + `/poli/`+page.props.auth.user.id)
        .then((response) => {
            data.poli = response.data as Poli;
        })
})


</script>
<template>
  <div >
    <SidebarHeader></SidebarHeader>
    <SidebarItemSection name="APP POLI" :subname="data.poli.nama">
      <SidebarItem title="Dashboard" to="/poli">
        <AllAppIcon class=" text-black" />
      </SidebarItem>
      <SidebarItem title="Rekam Medik" to="/poli/rekammedik">
        <MedicalIcon class="w-5 h-5" />
      </SidebarItem>
    </SidebarItemSection>
  </div>
</template>