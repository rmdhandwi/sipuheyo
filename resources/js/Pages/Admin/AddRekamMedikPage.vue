<script setup>
import Layout from "@/dashboard/Layout.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { onMounted, reactive } from "vue";
import AutoComplete from "@/Components/AutoComplete.vue";
import Tab from "@/Components/Tab.vue";
import RekamMedik from "@/Models/RekamMedik";
import AddIcon from "@/Icons/AddIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Helper from "@/heper";
import PrinterIcon from "@/Icons/PrinterIcon.vue";
import PrintResep from "@/Components/PrintResep.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    dokters: {
        type: Array,
    },
    polis: {
        type: Array,
    },
    pasiens: {
        type: Array,
    },
    obats: {
        type: Array,
    },

    rekammedik: RekamMedik,
});

const selectedTab = reactive({ id: 1 });

const form = useForm({
    id: 0,
    kode: "",
    nama: "",
    dokter_id: "",
    pasien_id: "",
    poli_id: "",
    tanggal: new Date().toISOString().split("T")[0],
    konsultasi_berikut: null,
    kondisi: { berat: 0, tinggi: 0, lingkar_badan: 0 },
    keluhan: [],
    penanganan: [],
    resep: [],
});

function addNewItem() {
    console.log("siap");
    window.location = "/admin/rekammedik/add";
}

function backAction(params) {
    window.location = "/admin/rekammedik";
}

function onChange(event) {
    if (props.rekammediks) {
        var poli = props.rekammediks.find((x) => x.id == event.target.value);
        if (poli) {
            form.dokter_id = poli.dokter_id;
        }
    }
}

const save = () => {
    if (form.id <= 0) {
        form.post(route("admin.rekammedik.post"), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500,
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
                        timer: 1500,
                    });
                }
            },
        });
    } else {
        form.put(route("admin.rekammedik.put", form.id), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
        });
    }
};

const selectPasien = (pasien) => {
    form.pasien_id = pasien.id;
};

function addKeluhan() {
    if (!form.keluhan) {
        form.keluhan = [];
    }
    form.keluhan.push({ value: "" });
}

function deleteKeluhan(item) {
    var indexOf = form.keluhan.indexOf(item);
    var data = form.keluhan.splice(indexOf, 1);
}

function addPenanganan() {
    if (!form.penanganan) {
        form.penanganan = [];
    }
    form.penanganan.push({ value: "" });
}

function deletePenanganan(item) {
    var indexOf = form.penanganan.indexOf(item);
    form.penanganan.splice(indexOf, 1);
}

function addResep() {
    if (!form.resep) {
        form.resep = [];
    }
    form.resep.push({ obat_id: 0, dosis: "", catatan: "" });
}

function deleteResep(item) {
    var indexOf = form.resep.indexOf(item);
    form.resep.splice(indexOf, 1);
}

onMounted(() => {
    

    if (props.rekammedik) {
        form.id = props.rekammedik.id;
        form.kode = Helper.getKode(props.rekammedik.id, "RekamMedik");
        form.pasien_id = props.rekammedik.pasien_id;
        form.dokter_id = props.rekammedik.dokter_id;
        form.poli_id = props.rekammedik.poli_id;
        form.tanggal = props.rekammedik.tanggal;
        form.hasil_lab = props.rekammedik.hasil_lab;
        form.konsultasi_berikut = props.rekammedik.konsultasi_berikut;
        form.keluhan = JSON.parse(props.rekammedik.keluhan);
        form.kondisi = JSON.parse(props.rekammedik.kondisi);
        form.penanganan = JSON.parse(props.rekammedik.penanganan);
        form.resep = JSON.parse(props.rekammedik.resep);
    }
});

const tabs = [
    { id: 1, name: "Kondisi Dan Keluhan" },
    { id: 2, name: "Penanganan" },
    { id: 3, name: "Resep" },
    { id: 4, name: "Jadwal Berobat" },
];

const selectTab = (param) => {
    selectedTab.id = param.id;
};

const printResep = () => {
    if (form.resep) {
        window.print();
    }
};
</script>

<template>
    <Layout class="noprint">
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">TAMBAH/EDIT REKAM MEDIK</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <form @submit.prevent="save">
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Kode</label>
                                <input
                                    type="text"
                                    v-model="form.kode"
                                    disabled
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Pasien</label>
                                <AutoComplete
                                    v-on:on-select-pasien="selectPasien"
                                    :value="form.pasien_id"
                                    :pasiens="props.pasiens"
                                ></AutoComplete>
                                <InputError
                                    :message="form.errors['pasien_id']"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Nama Poli</label>
                                <select
                                    type="text"
                                    v-model="form.poli_id"
                                    @change="onChange($event)"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
                                        :value="item.id"
                                        v-for="item in polis"
                                    >
                                        {{ item.nama }}
                                    </option>
                                </select>
                                <InputError :message="form.errors['poli_id']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Dokter</label>
                                <select
                                    type="text"
                                    v-model="form.dokter_id"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
                                        :value="item.id"
                                        v-for="item in dokters"
                                    >
                                        {{ item.nama }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors['dokter_id']"
                                />
                            </div>

                            <div class="m-2 flex justify-end">
                                <button
                                    type="button"
                                    @click="backAction()"
                                    class="mx-1 rounded-full border border-rose-300 px-5 py-1 text-white bg-rose-500"
                                >
                                    Kembali
                                </button>
                                <button
                                    type="submit"
                                    class="mx-1 rounded-full border border-sky-500 px-5 py-1 text-white bg-sky-700"
                                >
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="p-5">
            <Tab
                class="px-5"
                :items="tabs"
                :tabActive="selectedTab.id"
                @onClickTab="selectTab"
            />
            <div v-if="selectedTab.id == 1">
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">KONDISI</h1>
                </div>
                <ul class="p-5 mt-5 shadow-md">
                    <li class="mb-3">
                        <div class="flex items-center">
                            <h6>Berat Badan</h6>
                            <input
                                type="number"
                                v-model="form.kondisi.berat"
                                class="w-20 mx-3 mr-10 rounded-lg bg-transparent text-neutral-700"
                            />
                            <h6>Tinggi Badan</h6>
                            <input
                                type="number"
                                v-model="form.kondisi.tinggi"
                                class="mx-3 mr-10 rounded-lg bg-transparent text-neutral-700"
                            />
                            <h6>Lingkar Badan</h6>
                            <input
                                type="number"
                                v-model="form.kondisi.lingkar_badan"
                                class="mx-3 rounded-lg bg-transparent text-neutral-700"
                            />
                            <h6>Tekanan Darah</h6>
                            <input
                                type="text"
                                v-model="form.kondisi.tekanan_darah"
                                class="mx-3 rounded-lg bg-transparent text-neutral-700"
                            />
                        </div>
                    </li>
                </ul>
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">KELUHAN</h1>
                    <AddIcon
                        class="w-7 text-teal-500 cursor-pointer"
                        @click="addKeluhan()"
                    />
                </div>
                <ul class="p-5 mt-5 shadow-md">
                    <InputError :message="form.errors['keluhan']" />
                    <li v-for="(item, key) in form.keluhan" class="flex gap-1">
                        <input
                            type="text"
                            :value="key + 1"
                            disabled
                            class="w-12 rounded-lg bg-transparent text-neutral-700"
                        />
                        <input
                            type="text"
                            v-model="item.value"
                            @change="onChangeKeluhan(item)"
                            class="w-full rounded-lg bg-transparent text-neutral-700"
                        />
                        <DeleteIcon
                            @click="deleteKeluhan(item)"
                            class="w-7 text-red-500"
                        />
                    </li>
                </ul>
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">Hasil Lab</h1>
                </div>
                <ul class="p-5">
                    <li class="mb-3">
                        <img
                            class="mt-3 w-full h-auto"
                            :src="`/storage/${form.hasil_lab}`"
                        />
                    </li>
                </ul>
            </div>
            <div v-if="selectedTab.id == 2">
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">PENANGANAN</h1>
                    <!-- <AddIcon class=" w-7 text-teal-500 cursor-pointer" @click="addPenanganan()" /> -->
                </div>
                <ul class="p-5 mt-5 shadow-md">
                    <li
                        v-for="(item, key) in form.penanganan"
                        class="flex gap-1"
                    >
                        <input
                            type="text"
                            :value="key + 1"
                            disabled
                            class="w-12 rounded-lg bg-transparent text-neutral-700"
                        />
                        <input
                            type="text"
                            v-model="item.value"
                            @change="onChangePenanganan(item)"
                            class="w-full rounded-lg bg-transparent text-neutral-700"
                        />
                        <DeleteIcon
                            @click="deletePenanganan(item)"
                            class="w-7 text-red-500"
                        />
                    </li>
                </ul>
            </div>
            <div v-if="selectedTab.id == 3">
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">RESEP</h1>
                    <!-- <AddIcon class=" w-7 text-teal-500 cursor-pointer" @click="addResep()" /> -->
                    <PrinterIcon
                        v-if="form.resep && form.resep.length"
                        class="w-6 cursor-pointer text-amber-500"
                        @click="printResep()"
                    ></PrinterIcon>
                </div>
                <ul class="p-5 mt-5 shadow-md">
                    <li v-for="(item, key) in form.resep" class="flex gap-1">
                        <input
                            type="text"
                            :value="key + 1"
                            disabled
                            class="w-12 rounded-lg bg-transparent text-neutral-700"
                        />
                        <select
                            type="text"
                            v-model="item.obat_id"
                            class="w-1/2 rounded-lg bg-transparent text-neutral-700"
                        >
                            <option :value="obat.id" v-for="obat in obats">
                                {{ obat.nama }} {{ obat.dosis }} ({{
                                    obat.kemasan
                                }})
                            </option>
                        </select>
                        <input
                            type="text"
                            v-model="item.dosis"
                            placeholder="dosis"
                            class="w-1/2 rounded-lg bg-transparent text-neutral-700"
                        />
                        <input
                            type="text"
                            v-model="item.catatan"
                            placeholder="durasi"
                            class="w-1/2 rounded-lg bg-transparent text-neutral-700"
                        />
                    </li>
                </ul>
            </div>
            <div v-if="selectedTab.id == 4">
                <div class="p-5 mt-5 flex justify-between shadow-md">
                    <h1 class="text-2xl">JADWAL BEROBAT BERIKUT</h1>
                </div>
                <div class="flex flex-col p-3">
                    <label class="mb-2">Tanggal</label>
                    <input
                        type="datetime-local"
                        v-model="form.konsultasi_berikut"
                        disabled
                        class="rounded-lg bg-transparent text-neutral-700"
                    />
                </div>
            </div>
        </div>
    </Layout>

    <PrintResep
        v-if="rekammedik && rekammedik.resep"
        :obats="props.obats"
        :rekammedik="props.rekammedik"
    ></PrintResep>
</template>
