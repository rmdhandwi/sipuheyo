<script setup>
import DokterLayout from '@/Layouts/DokterLayout.vue';
import EditIcon from '@/Icons/EditIcon.vue';
import DeleteIcon from '@/Icons/DeleteIcon.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3'
import { ref, computed, reactive } from 'vue';
import DatePicker from '@/Components/DatePicker.vue';
import axios from 'axios'
import Helper from '@/heper';
import AddIcon from '@/Icons/AddIcon.vue';
import PrinterIcon from '@/Icons/PrinterIcon.vue';
import Search from '@/Components/Search.vue';
import LogoKota from '@/Icons/LogoKota.vue';
import LogoPuskesmas from '@/Icons/LogoPuskesmas.vue';
import Dokter from '@/Models/Dokter';

const props = defineProps({
    dokter: {
        type: Dokter
    }
})

const data = reactive({ rekamMedik: Array, poli: {} });

const onChangeDate = (date) => {
    axios
        .get(Helper.apiUrl + `/dokter/jadwalberobatbydate/${props.dokter.id}/${date}`)
        .then((response) => {
            data.rekamMedik = response.data;
            console.log(data.rekamMedik);
        })


};

const printReport = () => {

    if (data.rekamMedik && data.rekamMedik.length > 0) {
        window.print();
    }

}


</script>


<template>
    <DokterLayout class="noprint">
        <div class=" mt-5 flex justify-between">
            <h1 class="text-xl">LAPORAN KUNJUNGAN BEROBAT BERIKUT</h1>
            <div class="flex">
                <PrinterIcon class=" cursor-pointer text-amber-600 w-12" @click="printReport()"></PrinterIcon>
            </div>
        </div>
        <div class="flex items-center">
            <label class="mx-2 ml-10">Tanggal</label>
            <DatePicker v-on:on-change-date="onChangeDate"></DatePicker>
        </div>
        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Kunjungan Berikut
                            </th>
                            <th scope="col"
                                class="border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Pasien
                            </th>
                            <th scope="col"
                                class="border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Poli
                            </th>
                            <th scope="col"
                                class=" w-auto border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Dokter
                            </th>
                            <th scope="col"
                                class=" w-20 border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data.rekamMedik">
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.konsultasi_berikut }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.pasien.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.poli.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.dokter.nama }}</p>
                            </td>

                            <td class="border-b border-gray-200  p-3 text-sm flex">
                                <a :href="'/admin/rekammedik/add/' + item.id"
                                    class=" text-amber-500 hover:text-amber-700">
                                    <EditIcon class=" w-5" />
                                </a>
                                <a @click="deleteItem(item)" class=" cursor-pointer text-rose-600 hover:text-rose-900">
                                    <DeleteIcon class=" w-5" />
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </DokterLayout>


    <div class="print">
        <div>
            <div class=" w-full flex justify-between border-b-2 border-gray-900">
                <LogoKota class=" w-16 h-16"></LogoKota>
                <div class=" text-center">
                    <h2>PEMERINTAH KOTA JAYAPURA</h2>
                    <h2>DINAS KESEHATAN</h2>
                    <h2>PUSKESMAS HEBEYBHULU</h2>
                    <div class=" text-sm">Jln. Yoka - Arso , Kampung Yoka, DIstrik Heram, Kota Jayapura - Papua</div>
                    <div class=" text-sm">Kode Pos : 99531, NO Telp 081248227115</div>
                    <div class=" text-sm">email : puskesmayoka@gmail.com</div>
                </div>
                <LogoPuskesmas class=" w-16 h-16"></LogoPuskesmas>
            </div>
            <hr />

            <div>
                <div class="flex">
                    <div class=" w-32">Nama Poli</div>
                    <div>: {{ data.poli.nama }}</div>
                </div>
                <div class="flex">
                    <div class=" w-32">Penyakit</div>
                    <div>: {{ data.poli.penyakit }}</div>
                </div>
                <div class="flex">
                    <div class=" w-32">Tanggal</div>
                    <div>:</div>
                </div>
            </div>


            <table class="w-full mt-3">
                <thead>
                    <tr>
                        <th>
                            Kunjungan Berikut
                        </th>
                        <th>
                            Pasien
                        </th>
                        <th>
                            Poli
                        </th>
                        <th>
                            Dokter
                        </th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in data.rekamMedik">
                        <td>
                            <p class="whitespace-nowrap">{{ item.konsultasi_berikut }}</p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">{{ item.pasien.nama }}</p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">{{ item.poli.nama }}</p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">{{ item.dokter.nama }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>