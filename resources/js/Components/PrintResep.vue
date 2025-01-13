<script setup>

import LogoKota from '@/Icons/LogoKota.vue';
import LogoPuskesmas from '@/Icons/LogoPuskesmas.vue';
import RekamMedik from '@/Models/RekamMedik';
import Helper from '@/heper';

const props = defineProps({
    obats: {
        type: Array,
    },

    rekammedik: RekamMedik
})

const obat =(id)=>{

    var obt = props.obats.find(x=>x.id==id);
    if(obt){
        return  obt.nama+" "+ obt.dosis+" (" +obt.kemasan +")" ;
    }
}


const getUmur=(tgl)=>{
    var now = new Date();
    tgl = new Date(tgl);
    return now.getFullYear()- tgl.getFullYear(); 

}

</script>

<template>
    <div class="print">
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

        <h1 class=" mt-5 text-center underline text-lg">RESEP DOKTER</h1>
        <div class="flex justify-between">
            <div>
                <div class="flex">
                    <div class="w-28 text-sm">Nama Poli</div>
                    <div class="text-sm">: {{ props.rekammedik.poli.nama }}</div>
                </div>
                <div class="flex">
                    <div class=" w-28 text-sm">Penyakit</div>
                    <div class="text-sm">: {{ props.rekammedik.poli.penyakit }}</div>
                </div>
            </div>
            <div>
                <div class="flex">
                    <div class=" w-28 text-sm">Nomor Resep</div>
                    <div class="text-sm">: {{ Helper.getResepKode(rekammedik.id)}}</div>
                </div>
                <div class="flex">
                    <div class=" w-28 text-sm">Tanggal</div>
                    <div class="text-sm">: {{ props.rekammedik.tanggal}}</div>
                </div>
            </div>
        </div>
        <hr class="mt-2" />

        <h1 class="mt-3 mb-2 underline text-sky-800" >INFORMASI PASIEN</h1>    
        <div class="flex justify-between">
            <div>
                <div class="flex">
                    <div class=" w-28 text-sm">Nama Pasien</div>
                    <div class="text-sm">: {{ props.rekammedik.pasien.nama }}</div>
                </div>
                <div class="flex">
                    <div class=" w-28 text-sm">TTL</div>
                    <div class="text-sm">: {{ props.rekammedik.pasien.tempat_lahir }}, {{ props.rekammedik.pasien.tanggal_lahir }}</div>
                </div>
                <div class="flex">
                    <div class=" w-28 text-sm">Jenis Kelamin</div>
                    <div class="text-sm">: {{ props.rekammedik.pasien.jk }}</div>
                </div>
            </div>
            <div>
                <div class="flex">
                    <div class=" w-28 text-sm">Umur</div>
                    <div class="text-sm">: {{ getUmur(rekammedik.pasien.tanggal_lahir)}}</div>
                </div>
                <div class="flex">
                    <div class=" w-28 text-sm">Alamat</div>
                    <div class="text-sm">: {{ props.rekammedik.pasien.alamat}}</div>
                </div>
            </div>
        </div>

        <hr class="mt-2" />

        <table class="w-full mt-3">
            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        Obat
                    </th>
                    <th>
                        Dosis
                    </th>
                    <th>
                        Durasi
                    </th>

                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, key) in JSON.parse(props.rekammedik.resep)">
                    <td>
                        <p class="whitespace-nowrap text-sm text-center">{{ key + 1 }}</p>
                    </td>
                    <td>
                        <p class="whitespace-nowrap text-sm">
                            {{ obat(item.obat_id) }}    
                        </p>
                    </td>
                    <td>
                        <p class="whitespace-nowrap text-sm">
                            {{ item.dosis }}
                        </p>
                    </td>
                    <td>
                        <p class="whitespace-nowrap text-sm">
                            {{ item.catatan }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</template>