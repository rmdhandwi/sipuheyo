<script setup>
import DokterLayout from "@/Layouts/DokterLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { reactive, onMounted } from "vue";

const props = defineProps({
    rekammedik: Object,
    obats: Array,
    poli: Object,
});

function backAction(params) {
    window.location = "/dokter/rekammedik";
}

const selectedTab = reactive({ id: 1 });
const isManualResep = reactive({ value: false }); // Ensure reactivity for the checkbox

const form = useForm({
    id: 0,
    konsultasi_berikut: null,
    kondisi: { berat: 0, tinggi: 0, lingkar_badan: 0, tekanan_darah: "" },
    keluhan: [],
    penanganan: [],
    resep: [],
    resep_manual: null,
    hasil_lab: null,
});

// Load data on component mount
onMounted(() => {
    if (props.rekammedik) {
        const rekam = props.rekammedik;
        form.id = rekam.id;
        form.kode = rekam.kode;
        form.pasien_id = rekam.pasien_id;
        form.dokter_id = rekam.dokter_id;
        form.poli_id = rekam.poli_id;
        form.tanggal = rekam.tanggal;
        form.kondisi = JSON.parse(rekam.kondisi) || {};
        form.keluhan = JSON.parse(rekam.keluhan) || [];
        form.penanganan = JSON.parse(rekam.penanganan) || [];
        form.resep = JSON.parse(rekam.resep) || [];
        form.resep_manual = rekam.resep_manual || "";
        form.konsultasi_berikut = rekam.konsultasi_berikut || "";
        form.hasil_lab = rekam.hasil_lab || "";

        isManualResep.value = !!form.resep_manual.trim();

        if (form.kondisi.length === 0) {
            form.kondisi.push({ value: "" });
        }

        if (form.keluhan.length === 0) {
            form.keluhan.push({ value: "" });
        }

        if (form.penanganan.length === 0) {
            form.penanganan.push({ value: "" });
        }

        if (form.resep.length === 0) {
            form.resep.push({ obat: "", dosis: "", catatan: "" });
        }
    }
});

// Add, remove keluhan (complaints) and penanganan (treatment)
function addKeluhan() {
    form.keluhan.push({ value: "" });
}
function deleteKeluhan(item) {
    form.keluhan = form.keluhan.filter((kel) => kel !== item);
}

function addPenanganan() {
    form.penanganan.push({ value: "" });
}
function deletePenanganan(item) {
    form.penanganan = form.penanganan.filter((pen) => pen !== item);
}

function addResep() {
    form.resep.push({ obat: "", dosis: "", catatan: "" });
}
function deleteResep(item) {
    form.resep = form.resep.filter((res) => res !== item);
}

// Validation function for each tab
function validateTab() {
    switch (selectedTab.id) {
        case 1: // Kondisi dan Keluhan
            if (
                !form.kondisi.berat ||
                !form.kondisi.tinggi ||
                !form.kondisi.lingkar_badan
            ) {
                Swal.fire("Error", "Semua data kondisi harus diisi.", "error");
                return false;
            }
            if (
                !form.keluhan.length ||
                form.keluhan.some((kel) => !kel.value.trim())
            ) {
                Swal.fire(
                    "Error",
                    "Setidaknya satu keluhan harus diisi.",
                    "error"
                );
                return false;
            }
            break;
        case 2: // Penanganan
            if (
                !form.penanganan.length ||
                form.penanganan.some((pen) => !pen.value.trim())
            ) {
                Swal.fire(
                    "Error",
                    "Setidaknya satu penanganan harus diisi.",
                    "error"
                );
                return false;
            }
            break;
        case 3: // Resep
            if (!isManualResep.value) {
                if (
                    !form.resep.length ||
                    form.resep.some((res) => !res.obat_id || !res.dosis.trim())
                ) {
                    Swal.fire(
                        "Error",
                        "Setidaknya satu resep harus diisi.",
                        "error"
                    );
                    return false;
                }
            } else if (!form.resep_manual.trim()) {
                Swal.fire("Error", "Resep manual harus diisi.", "error");
                return false;
            }
            break;
    }
    return true;
}

// Validate all tabs before saving
function validateAll() {
    const requiredTabs = [1, 2, 3];
    for (const tabId of requiredTabs) {
        selectedTab.id = tabId;
        if (!validateTab()) return false;
    }
    return true;
}

// Save function
function save() {
    if (!validateAll()) return;

    form.put(route("dokter.rekammedik.put", form.id), {
        onSuccess: () => {
            Swal.fire("Success", "Data berhasil disimpan.", "success");
        },
        onError: (errors) => {
            Swal.fire(
                "Error",
                errors.error || "Gagal menyimpan data.",
                "error"
            );
        },
    });
}

// Switch between tabs
function switchTab(tab) {
    if (!validateTab()) return;
    selectedTab.id = tab.id;
}

const tabs = [
    { id: 1, name: "Kondisi dan Keluhan" },
    { id: 2, name: "Penanganan" },
    { id: 3, name: "Resep" },
    { id: 4, name: "Jadwal Berobat Selanjutnya" },
    { id: 5, name: "Hasil Lab" },
];

function getDate(dateString) {
    const date = new Date(dateString);

    // Mengambil Tahun, Bulan, dan Tanggal
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);

    return `${day}/${month}/${year}`;
}
</script>

<template>
    <Head title="Dokter Rekammedik" />
    <DokterLayout :poli="props.poli" :dokter="props.dokter">
        <div class="p-5">
            <h1 class="text-xl font-bold">Detail Rekam Medik</h1>
            <div class="flex justify-between items-center my-2">
                <div>
                    <h1 class="text-xl text-black">Nomor rekam Medik</h1>
                    <h1 class="text-xl text-gray-500">
                        {{ rekammedik.pasien.rekammedik || "-" }}
                    </h1>
                </div>
                <div>
                    <h1 class="text-xl text-black">Tanggal Berobat</h1>
                    <h1 class="text-xl text-gray-500">
                        {{ getDate(rekammedik.tanggal) || "-" }}
                    </h1>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-5">
                <!-- Informasi Umum -->
                <div class="grid grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="font-bold">Antrian:</label>
                        <p class="bg-gray-100 p-2 rounded">
                            {{ rekammedik.antrian || "-" }}
                        </p>
                    </div>
                    <div>
                        <label class="font-bold">Nama Poli:</label>
                        <p class="bg-gray-100 p-2 rounded">
                            {{ rekammedik.poli.nama || "-" }}
                        </p>
                    </div>
                    <div>
                        <label class="font-bold">Pasien:</label>
                        <p class="bg-gray-100 p-2 rounded">
                            {{ rekammedik.pasien.nama || "-" }}
                        </p>
                    </div>
                    <div>
                        <label class="font-bold">Dokter:</label>
                        <p class="bg-gray-100 p-2 rounded">
                            {{ rekammedik.dokter.nama || "-" }}
                        </p>
                    </div>
                </div>

                <!-- Navigasi Tabs -->
                <div class="border-b mb-5 flex space-x-2">
                    <button
                        v-for="tab in tabs"
                        :key="tab.id"
                        @click="switchTab(tab)"
                        :class="[
                            'px-4 py-2',
                            tab.id === selectedTab.id
                                ? 'text-white border-b-2 bg-blue-700 border-blue-500'
                                : 'text-gray-600',
                        ]"
                    >
                        {{ tab.name }}
                    </button>
                </div>

                <!-- Content Tabs -->
                <form @submit.prevent="save">
                    <div class="mt-5 gap-2 flex justify-end">
                        <button
                            type="button"
                            @click="backAction"
                            class="bg-red-700 text-white px-4 py-2 rounded-md"
                        >
                            Kembali
                        </button>

                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md"
                        >
                            Simpan
                        </button>
                    </div>

                    <div v-if="selectedTab.id === 1">
                        <!-- Tab 1: Kondisi dan Keluhan -->
                        <div class="mb-5">
                            <h2 class="text-lg font-bold mb-3">Kondisi</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium mb-1"
                                        >Berat Badan (kg)</label
                                    >
                                    <input
                                        type="number"
                                        v-model="form.kondisi.berat"
                                        class="w-full border-gray-300 rounded px-3 py-2"
                                    />
                                </div>
                                <div>
                                    <label class="block font-medium mb-1"
                                        >Tinggi Badan (cm)</label
                                    >
                                    <input
                                        type="number"
                                        v-model="form.kondisi.tinggi"
                                        class="w-full border-gray-300 rounded px-3 py-2"
                                    />
                                </div>
                                <div>
                                    <label class="block font-medium mb-1"
                                        >Lingkar Badan (cm)</label
                                    >
                                    <input
                                        type="number"
                                        v-model="form.kondisi.lingkar_badan"
                                        class="w-full border-gray-300 rounded px-3 py-2"
                                    />
                                </div>
                                <div>
                                    <label class="block font-medium mb-1"
                                        >Tekanan Darah</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form.kondisi.tekanan_darah"
                                        class="w-full border-gray-300 rounded px-3 py-2"
                                    />
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold mb-3">Keluhan</h2>
                            <div class="space-y-2">
                                <div
                                    v-for="(item, index) in form.keluhan"
                                    :key="index"
                                    class="flex items-center space-x-3"
                                >
                                    <input
                                        v-model="item.value"
                                        placeholder="Keluhan"
                                        class="flex-1 border-gray-300 rounded px-3 py-2"
                                    />
                                    <button
                                        type="button"
                                        @click="deleteKeluhan(item)"
                                        class="text-red-500 font-bold"
                                    >
                                        Hapus
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    @click="addKeluhan"
                                    class="text-teal-600 font-semibold"
                                >
                                    + Tambah Keluhan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="selectedTab.id === 2">
                        <!-- Tab 2: Penanganan -->
                        <h2 class="text-lg font-bold mb-3">Penanganan</h2>
                        <div class="space-y-2">
                            <div
                                v-for="(item, index) in form.penanganan"
                                :key="index"
                                class="flex items-center space-x-3"
                            >
                                <input
                                    v-model="item.value"
                                    placeholder="Penanganan"
                                    class="flex-1 border-gray-300 rounded px-3 py-2"
                                />
                                <button
                                    type="button"
                                    @click="deletePenanganan(item)"
                                    class="text-red-500 font-bold"
                                >
                                    Hapus
                                </button>
                            </div>
                            <button
                                type="button"
                                @click="addPenanganan"
                                class="text-teal-600 font-semibold"
                            >
                                + Tambah Penanganan
                            </button>
                        </div>
                    </div>

                    <div v-if="selectedTab.id === 3">
                        <!-- Tab 3: Resep -->
                        <h2 class="text-lg font-bold mb-3">Resep</h2>

                        <!-- Checkbox for manual prescription -->
                        <div class="flex items-center space-x-3 mb-5">
                            <input
                                type="checkbox"
                                id="manualResep"
                                v-model="isManualResep.value"
                                class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                            />
                            <label
                                for="manualResep"
                                class="text-gray-700 font-medium text-sm"
                            >
                                Resep Manual
                            </label>
                        </div>

                        <!-- Automatic prescription -->
                        <div class="space-y-2">
                            <div
                                v-for="(item, index) in form.resep"
                                :key="index"
                                class="flex items-center space-x-3"
                            >
                                <select
                                    v-model="item.obat_id"
                                    class="flex-1 border-gray-300 rounded px-3 py-2"
                                >
                                    <option
                                        v-for="obat in obats"
                                        :key="obat.id"
                                        :value="obat.nama"
                                    >
                                        {{ obat.nama }} - {{ obat.dosis }} ({{
                                            obat.kemasan
                                        }})
                                    </option>
                                </select>

                                <input
                                    v-model="item.dosis"
                                    placeholder="Aturan Minum"
                                    class="w-40 border-gray-300 rounded px-3 py-2"
                                />
                                <input
                                    v-model="item.catatan"
                                    placeholder="Catatan"
                                    class="w-40 border-gray-300 rounded px-3 py-2"
                                />
                                <button
                                    type="button"
                                    @click="deleteResep(item)"
                                    class="text-red-500 font-bold"
                                >
                                    Hapus
                                </button>
                            </div>
                            <button
                                type="button"
                                @click="addResep"
                                class="text-teal-600 font-semibold"
                            >
                                + Tambah Resep
                            </button>
                        </div>

                        <!-- Manual prescription -->
                        <div v-if="isManualResep.value">
                            <textarea
                                v-model="form.resep_manual"
                                class="w-full h-32 border rounded p-2 mt-4"
                                placeholder="Tuliskan resep manual di sini"
                            ></textarea>
                        </div>
                    </div>

                    <div v-if="selectedTab.id == 4">
                        <div class="p-5 mt-5 flex justify-between shadow-md">
                            <h1 class="text-2xl">JADWAL BEROBAT SELANJUTNYA</h1>
                        </div>
                        <div class="flex flex-col p-3">
                            <label class="mb-2">Tanggal dan Waktu</label>
                            <input
                                type="datetime-local"
                                v-model="form.konsultasi_berikut"
                                class="rounded-lg bg-transparent"
                            />
                        </div>
                    </div>

                    <!-- Tab 5: Lab Results -->
                    <div v-if="selectedTab.id === 5">
                        <h2 class="text-lg font-bold mb-3">Hasil Lab</h2>
                        <div v-if="form.hasil_lab">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="border p-3 rounded">
                                    <img
                                        :src="form.hasil_lab"
                                        :alt="'Lab result ' + (index + 1)"
                                        class="w-full h-auto rounded"
                                    />
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <p>Tidak Ada Hasil Rekam Lab</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </DokterLayout>
</template>
