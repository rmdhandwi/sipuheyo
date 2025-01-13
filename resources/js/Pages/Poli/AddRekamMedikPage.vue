<script setup>
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { onMounted, reactive, ref } from 'vue';
import AutoComplete from '@/Components/AutoComplete.vue';
import Tab from '@/Components/Tab.vue';
import RekamMedik from '@/Models/RekamMedik';
import Pasien from '@/Models/Pasien';
import AddIcon from '@/Icons/AddIcon.vue';
import DeleteIcon from '@/Icons/DeleteIcon.vue';
import Helper from '@/heper';
import PrinterIcon from '@/Icons/PrinterIcon.vue';
import PrintResep from '@/Components/PrintResep.vue';
import PasienLayout from '@/Layouts/PasienLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PoliLayout from '@/Layouts/PoliLayout.vue';
import { closeSidebar } from '@/dashboard/store';
import axios from 'axios';

const props = defineProps({
    polis: {
        type: Array
    },
    pasien: {
        type: Pasien,
    },
    dokters: {
        type: Array,
    },
    obats: {
        type: Array,
    },
    rekammedik: RekamMedik
})

const selectedTab = reactive({ id: 1 });

const form = useForm({
    "id": 0,
    "kode": '',
    "nama": '',
    "dokter_id": '',
    "pasien_id": 0,
    "poli_id": '',
    "tanggal": new Date().toISOString().split('T')[0],
    'konsultasi_berikut': null,
    'kondisi': { berat: 0, tinggi: 0, lingkar_badan: 0, tekanan_darah: '' },
    'keluhan': [],
    'penanganan': [],
    'resep': [],
    "status": '',
    "file": null
}
)


function addNewItem() {
    console.log("siap");
    window.location = "/admin/rekammedik/add";
}


function backAction(params) {
    window.location = "/pasien";
}

function onChange(event) {
    if (props.rekammediks) {
        var poli = props.rekammediks.find(x => x.id == event.target.value);
        if (poli) {
            form.dokter_id = poli.dokter_id;
        }
    } else {
        var poli = props.polis.find(x => x.id == event.target.value);
        form.dokter_id = poli.dokter_id;
    }
}


function toFormData(form) {
    const formData = new FormData();

    // Append each field from the form to the FormData
    Object.keys(form).forEach((key) => {
        const value = form[key];

        if (value && typeof value === 'object' && !Array.isArray(value)) {
            // If the value is an object (like 'kondisi'), flatten it
            Object.keys(value).forEach((subKey) => {
                formData.append(`${key}[${subKey}]`, value[subKey]);
            });
        } else if (Array.isArray(value)) {
            // If the value is an array (like 'keluhan', 'penanganan', 'resep')
            value.forEach((item, index) => {
                formData.append(`${key}[${index}]`, item);
            });
        } else if (key === 'file' && value) {
            // Handle the file upload
            formData.append(key, value);
        } else {
            // For other simple fields, just append them directly
            formData.append(key, value);
        }
    });

    return formData;
}



const save = () => {

    if (form.id <= 0) {
        form.post(route('pasien.rekammedik.post'), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
                form.reset();
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // form.errors = err;
                }
            }
        });
    } else {

        // const formData = toFormData(form);
        form.put(route('poli.rekammedik.put', form.id), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // form.errors = err;
                }
            }

        });
    }
}


const selectPasien = (pasien) => {
    form.pasien_id = pasien.id;
}

function addKeluhan() {
    if (!form.keluhan) {
        form.keluhan = [];
    }
    form.keluhan.push({ value: '' });
}


function deleteKeluhan(item) {
    var indexOf = form.keluhan.indexOf(item);
    var data = form.keluhan.splice(indexOf, 1);
}


function addPenanganan() {
    if (!form.penanganan) {
        form.penanganan = [];
    }
    form.penanganan.push({ value: '' });
}


function deletePenanganan(item) {
    var indexOf = form.penanganan.indexOf(item);
    form.penanganan.splice(indexOf, 1);
}

function addResep() {
    if (!form.resep) {
        form.resep = [];
    }
    form.resep.push({ obat_id: 0, dosis: '', catatan: '' });
}


function deleteResep(item) {
    var indexOf = form.resep.indexOf(item);
    form.resep.splice(indexOf, 1);
}


onMounted(() => {
    if (props.rekammedik) {
        form.id = props.rekammedik.id;
        form.kode = Helper.getKode(props.rekammedik.id, 'RekamMedik');
        form.pasien_id = props.rekammedik.pasien_id;
        form.dokter_id = props.rekammedik.dokter_id;
        form.poli_id = props.rekammedik.poli_id;
        form.tanggal = props.rekammedik.tanggal;
        form.status = props.rekammedik.status;
        fileData.preview = `/storage/${props.rekammedik.hasil_lab}` ;
        form.konsultasi_berikut = props.rekammedik.konsultasi_berikut;
        form.kondisi = JSON.parse(props.rekammedik.kondisi);
        form.keluhan = JSON.parse(props.rekammedik.keluhan);
        form.penanganan = JSON.parse(props.rekammedik.penanganan);
        form.resep = JSON.parse(props.rekammedik.resep);
    }
});



const tabs = [
    { id: 1, name: 'Kondisi dan Keluhan' },
]

const selectTab = (param) => {
    selectedTab.id = param.id;
};

const printResep = () => {
    if (form.resep) {
        window.print();
    }
}




function convertFileToBase64(file) {
    const reader = new FileReader();
    reader.onload = function (event) {
        const binaryString = event.target.result;
        const base64String = btoa(binaryString);
        form.file = base64String;
    };
    reader.readAsBinaryString(file);
}

const fileX = ref(null);

const fileData = reactive({ example: null, image: false, preview: null });
const readFile = () => {
    var file = fileX.value.files[0];
    fileData.image=true;
    fileData.preview =  URL.createObjectURL(file);;
    convertFileToBase64(file).toString();
}


</script>

<template>

    <PoliLayout class="noprint">
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">PENDAFTAR BEROBAT</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow ">
                <form @submit.prevent="save">
                    <div class=" grid grid-cols-2">
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Kode</label>
                                <input type="text" v-model="form.kode" disabled
                                    class=" rounded-lg bg-transparent  text-neutral-700">
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Pasien</label>
                                <input type="text" v-model="pasien.nama" disabled
                                    class=" rounded-lg bg-transparent  text-neutral-700">
                            </div>

                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Nama Poli</label>
                                <select type="text" v-model="form.poli_id" @change="onChange($event)"
                                    class=" rounded-lg bg-transparent  text-neutral-700 ">
                                    <option :value="item.id" v-for="item in polis">{{ item.nama }}</option>
                                </select>
                                <InputError :message="form.errors['poli_id']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Dokter</label>
                                <select disabled type="text" v-model="form.dokter_id" required
                                    class="rounded-lg bg-transparent  text-neutral-700">
                                    <option :value="item.id" v-for="item in dokters">{{ item.nama }}</option>
                                </select>
                                <InputError :message="form.errors['dokter_id']" />
                            </div>
                            <div class="m-2 flex justify-end">
                                <button type="button" @click="backAction()"
                                    class="mx-1 rounded-full border  border-rose-300 px-5 py-1 text-white  bg-rose-500">Kembali</button>
                                <button type="submit"
                                    class=" mx-1 rounded-full border  border-sky-500 px-5 py-1  text-white bg-sky-700">Simpan</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="p-5">
            <Tab class="px-5" :items="tabs" :tabActive="selectedTab.id" @onClickTab="selectTab" />
            <div v-if="selectedTab.id == 1">
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">KONDISI</h1>
                </div>
                <ul class="p-5">
                    <li class="mb-3">
                        <div class="flex items-center">
                            <h6>Berat Badan</h6>
                            <input type="number" v-model="form.kondisi.berat"
                                class="  w-20 mx-3  mr-10 rounded-lg bg-transparent  text-neutral-700">
                            <h6>Tinggi Badan</h6>
                            <input type="number" v-model="form.kondisi.tinggi"
                                class=" mx-3 mr-10 rounded-lg bg-transparent  text-neutral-700">
                            <h6>Lingkar Badan</h6>
                            <input type="number" v-model="form.kondisi.lingkar_badan"
                                class=" mx-3 mr-10 rounded-lg bg-transparent  text-neutral-700">
                            <h6>Tekanan Darah</h6>
                            <input type="text" v-model="form.kondisi.tekanan_darah"
                                class=" mx-3 rounded-lg bg-transparent  text-neutral-700">
                        </div>
                    </li>
                </ul>


                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">KELUHAN</h1>
                    <AddIcon class=" w-7 text-teal-500 cursor-pointer" @click="addKeluhan()" />
                </div>
                <ul class="p-5 mt-5 shadow-md">
                    <InputError :message="form.errors['keluhan']" />
                    <li v-for="(item, key) in form.keluhan" class="flex gap-1">
                        <input type="text" :value="key + 1" disabled
                            class=" w-12 rounded-lg bg-transparent  text-neutral-700">
                        <input type="text" v-model="item.value" @change="onChangeKeluhan(item)"
                            class=" w-full rounded-lg bg-transparent  text-neutral-700">
                        <DeleteIcon @click="deleteKeluhan(item)" class="w-7 text-red-500" />
                    </li>

                </ul>
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">Hasil Lab</h1>
                </div>
                <ul class="p-5">
                    <li class="mb-3">
                        <input type="file" ref="fileX" @change="readFile()" />
                        <img class="mt-3 w-full  h-auto" :src="fileData.preview" />
                    </li>

                </ul>
            </div>
        </div>
    </PoliLayout>



    <PrintResep v-if="rekammedik && rekammedik.resep" :obats="props.obats" :rekammedik="props.rekammedik"></PrintResep>

</template>