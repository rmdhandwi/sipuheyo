<script setup>
import BackIcon from "@/Icons/BackIcon.vue";
import LogoKota from "@/Icons/LogoKota.vue";
import LogoPuskesmas from "@/Icons/LogoPuskesmas.vue";
import PrinterIcon from "@/Icons/PrinterIcon.vue";
import PoliLayout from "@/Layouts/PoliLayout.vue";
import RekamMedik from "@/Models/RekamMedik";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    rekammedik: RekamMedik,
    poli: Array,
    dokter: Array,
    auth: Object,
});

function getDate(dateString) {
    const date = new Date(dateString);

    // Mengambil Tahun, Bulan, dan Tanggal
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);

    return `${day}/${month}/${year}`;
}

function backAction() {
    window.location = "/poli/rekammedik/pasien/" + props.rekammedik[0].pasien_id;
}

const role = props.auth.user.role;

// Function to calculate age from birthdate
function calculateAge(birthDate) {
    const birthDateObj = new Date(birthDate);
    const currentDate = new Date();
    let age = currentDate.getFullYear() - birthDateObj.getFullYear();
    const monthDifference = currentDate.getMonth() - birthDateObj.getMonth();

    // Adjust age if birthday hasn't occurred yet this year
    if (
        monthDifference < 0 ||
        (monthDifference === 0 &&
            currentDate.getDate() < birthDateObj.getDate())
    ) {
        age--;
    }

    return age;
}

// Parse the rekammedik data
const kondisi = JSON.parse(props.rekammedik[0].kondisi);
const keluhan = JSON.parse(props.rekammedik[0].keluhan);
const penanganan = JSON.parse(props.rekammedik[0].penanganan);
const resep = JSON.parse(props.rekammedik[0].resep);

// Active tab state
const activeTab = ref("kondisi");

function printAll() {
    window.print();
}
</script>

<template>
    <Head title="Detail Rekam Medik" />
    <PoliLayout class="noprint" :poli="props.poli">
        <div class="p-5 mt-5 flex flex-col justify-center items-center">
            <h1 class="text-2xl font-semibold text-gray-800 uppercase">
                Detail Rekam Medik
            </h1>
        </div>

        <div class="p-5 mt-4 bg-gray-50 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <button type="button" @click="backAction">
                    <BackIcon class="cursor-pointer w-10" />
                </button>
                <button
                    @click="printAll"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-800 transition-all flex items-center justify-center gap-2"
                >
                    <PrinterIcon class="cursor-pointer w-6" />
                    Cetak
                </button>
            </div>

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 my-3"
            >
                <!-- Patient Name -->
                <div
                    class="p-4 bg-white rounded-md shadow-md border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700 text-xl">
                        Nomor Rekam Medik
                    </h3>
                    <p class="text-gray-500 text-xl">
                        {{ props.rekammedik[0].pasien.rekammedik }}
                    </p>
                </div>

                <!-- Age -->
                <div
                    class="p-4 bg-white rounded-md shadow-md border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700 text-xl">
                        Tanggal Berobat
                    </h3>
                    <p class="text-gray-500 text-xl">
                        {{ getDate(props.rekammedik[0].tanggal) }}
                    </p>
                </div>

                <div
                    class="p-4 bg-white rounded-md shadow-md border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700 text-xl">
                        Poli/Ruang
                    </h3>
                    <p class="text-gray-500 text-xl">
                        {{ props.rekammedik[0].poli.nama }}
                    </p>
                </div>
                <div
                    class="p-4 bg-white rounded-md shadow-md border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700 text-xl">Dokter</h3>
                    <p class="text-gray-500 text-xl">
                        {{ props.rekammedik[0].dokter.nama }}
                    </p>
                </div>
            </div>

            <!-- Patient Information Cards -->

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 my-6"
            >
                <!-- Patient Name -->
                <div
                    class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700">Nama Pasien</h3>
                    <p class="text-gray-500">
                        {{ props.rekammedik[0].pasien.nama }}
                    </p>
                </div>

                <!-- Age -->
                <div
                    class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700">Umur</h3>
                    <p class="text-gray-500">
                        {{
                            calculateAge(
                                props.rekammedik[0].pasien.tanggal_lahir
                            )
                        }}
                        Tahun
                    </p>
                </div>

                <!-- Gender -->
                <div
                    class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                >
                    <h3 class="font-semibold text-gray-700">Jenis Kelamin</h3>
                    <p class="text-gray-500">
                        {{ props.rekammedik[0].pasien.jk }}
                    </p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="p-5 mt-4 bg-gray-50 rounded-lg shadow-lg">
                <!-- Tab Buttons -->
                <div class="flex border-b mb-5">
                    <button
                        v-for="tab in [
                            'kondisi',
                            'keluhan',
                            'penanganan',
                            'resep',
                            'hasil_lab',
                        ]"
                        :key="tab"
                        @click="activeTab = tab"
                        :class="[
                            'px-4 py-2 font-semibold',
                            activeTab === tab
                                ? 'border-b-2 border-blue-500 text-blue-500'
                                : 'text-gray-600 hover:text-blue-500',
                        ]"
                        class="transition duration-300"
                    >
                        {{ tab.replace("_", " ").toUpperCase() }}
                    </button>
                </div>

                <!-- Tab Content -->
                <div v-if="activeTab === 'kondisi'">
                    <!-- Kondisi -->
                    <h3 class="text-lg font-semibold text-black mb-3">
                        Kondisi Pasien
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                        >
                            <h4 class="font-medium text-gray-700">
                                Berat Badan
                            </h4>
                            <p class="text-gray-500">{{ kondisi.berat }} kg</p>
                        </div>
                        <div
                            class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                        >
                            <h4 class="font-medium text-gray-700">
                                Tinggi Badan
                            </h4>
                            <p class="text-gray-500">{{ kondisi.tinggi }} cm</p>
                        </div>
                        <div
                            class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                        >
                            <h4 class="font-medium text-gray-700">
                                Lingkar Badan
                            </h4>
                            <p class="text-gray-500">
                                {{ kondisi.lingkar_badan }} cm
                            </p>
                        </div>
                        <div
                            class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                        >
                            <h4 class="font-medium text-gray-700">
                                Tekanan Darah
                            </h4>
                            <p class="text-gray-500">
                                {{ kondisi.tekanan_darah }} mmHg
                            </p>
                        </div>
                    </div>
                </div>

                <div v-else-if="activeTab === 'keluhan'">
                    <!-- Keluhan -->
                    <h3 class="text-lg font-semibold text-black mb-3">
                        Keluhan Pasien
                    </h3>
                    <div
                        v-if="keluhan && keluhan.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                    >
                        <div
                            v-for="(item, index) in keluhan"
                            :key="index"
                            class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                        >
                            <h4 class="font-medium text-gray-700">
                                {{ index + 1 }}. {{ item.value }}
                            </h4>
                        </div>
                    </div>
                    <div v-else class="p-4 bg-gray-100 rounded-lg">
                        <h4 class="font-medium text-gray-500 text-center">
                            Tidak ada keluhan yang tercatat.
                        </h4>
                    </div>
                </div>

                <div v-else-if="activeTab === 'penanganan'">
                    <!-- Penanganan -->
                    <h3 class="text-lg font-semibold text-black mb-3">
                        Penanganan
                    </h3>
                    <div
                        v-if="penanganan && penanganan.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                    >
                        <div
                            v-for="(item, index) in penanganan"
                            :key="index"
                            class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                        >
                            <h4 class="font-medium text-gray-700">
                                {{ index + 1 }}. {{ item.value }}
                            </h4>
                        </div>
                    </div>
                    <div v-else class="p-4 bg-gray-100 rounded-lg">
                        <h4 class="font-medium text-gray-500 text-center">
                            Tidak ada penanganan yang tercatat.
                        </h4>
                    </div>
                </div>

                <div v-else-if="activeTab === 'resep'">
                    <!-- Resep -->
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-semibold text-black">
                            Data Resep
                        </h3>
                        <button
                            @click="printResep"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition-all"
                        >
                            Cetak Resep
                        </button>
                    </div>

                    <div id="print-resep">
                        <!-- Header with logos and patient info for print -->
                        <div
                            class="print-header flex justify-between items-center border-b-2 border-gray-900 mb-4"
                        >
                            <!-- Logo Kota -->
                            <LogoKota class="w-16 h-16" />
                            <div class="text-center">
                                <h2 class="font-bold">
                                    PEMERINTAH KOTA JAYAPURA
                                </h2>
                                <h2 class="font-bold">DINAS KESEHATAN</h2>
                                <h2 class="font-bold">PUSKESMAS HEBEYBHULU</h2>
                                <div class="text-sm">
                                    Jln. Yoka - Arso, Kampung Yoka, Distrik
                                    Heram, Kota Jayapura - Papua
                                </div>
                                <div class="text-sm">
                                    Kode Pos : 99531, No Telp: 081248227115
                                </div>
                                <div class="text-sm">
                                    email: puskesmayoka@gmail.com
                                </div>
                            </div>
                            <!-- Logo Puskesmas -->
                            <LogoPuskesmas class="w-16 h-16" />
                        </div>

                        <!-- Patient and Doctor Info for print -->
                        <div class="text-center mb-4 print-info">
                            <h3 class="font-semibold text-lg">
                                Pasien: {{ props.rekammedik[0].pasien.nama }}
                            </h3>
                            <h4 class="text-md">
                                Dokter: {{ props.rekammedik[0].dokter.nama }}
                            </h4>
                            <p class="text-sm">
                                Tanggal: {{ new Date().toLocaleDateString() }}
                            </p>
                        </div>

                        <!-- Resep Obat Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column: Resep Obat -->
                            <div>
                                <h4
                                    class="text-lg font-semibold text-gray-700 mb-3"
                                >
                                    Resep Obat
                                </h4>
                                <div
                                    v-if="resep && resep.length > 0"
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4"
                                >
                                    <div
                                        v-for="(item, index) in resep"
                                        :key="index"
                                        class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                                    >
                                        <h4
                                            class="font-medium text-gray-700 mb-2"
                                        >
                                            <span class="font-semibold"
                                                >Obat:</span
                                            >
                                            {{ item.obat_id }}
                                        </h4>
                                        <p class="text-gray-600 mb-1">
                                            <span class="font-semibold"
                                                >Dosis:</span
                                            >
                                            {{ item.dosis }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-semibold"
                                                >Catatan:</span
                                            >
                                            {{ item.catatan }}
                                        </p>
                                    </div>
                                </div>
                                <div v-else class="p-4 bg-gray-100 rounded-lg">
                                    <h4
                                        class="font-medium text-gray-500 text-center"
                                    >
                                        Tidak ada resep yang tercatat.
                                    </h4>
                                </div>
                            </div>

                            <!-- Right Column: Resep Manual -->
                            <div>
                                <h4
                                    class="text-lg font-semibold text-gray-700 mb-3"
                                >
                                    Resep Manual
                                </h4>
                                <div
                                    v-if="props.rekammedik[0].resep_manual"
                                    class="space-y-4"
                                >
                                    <div
                                        class="p-4 bg-white rounded-lg shadow-lg border hover:border-black transition-all duration-300"
                                    >
                                        <h4
                                            class="font-medium text-gray-700 mb-2"
                                        >
                                            <span class="font-semibold">{{
                                                props.rekammedik[0].resep_manual
                                            }}</span>
                                        </h4>
                                    </div>
                                </div>
                                <div v-else class="p-4 bg-gray-100 rounded-lg">
                                    <h4
                                        class="font-medium text-gray-500 text-center"
                                    >
                                        Tidak ada resep manual yang tercatat.
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="activeTab === 'hasil_lab'">
                    <!-- Hasil Lab -->
                    <h3 class="text-lg font-semibold text-black mb-3">
                        Hasil Laboratorium
                    </h3>
                    <div v-if="props.rekammedik[0].hasil_lab">
                        <img
                            :src="props.rekammedik[0].hasil_lab"
                            :alt="
                                'Hasil lab untuk pasien ' +
                                props.rekammedik[0].pasien.nama
                            "
                            class="rounded-lg shadow-md w-full"
                        />
                    </div>
                    <p v-else class="text-gray-500">
                        Data hasil laboratorium belum tersedia.
                    </p>
                </div>
            </div>
        </div>
    </PoliLayout>

    <div id="print-all" class="p-6 bg-white shadow-lg rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center border-b-2 pb-4 mb-4">
            <!-- Logo Kota -->
            <LogoKota class="w-16 h-16" />
            <div class="text-center">
                <h2 class="font-bold text-lg">PEMERINTAH KOTA JAYAPURA</h2>
                <h2 class="font-bold text-lg">DINAS KESEHATAN</h2>
                <h2 class="font-bold text-lg">PUSKESMAS HEBEYBHULU</h2>
                <p class="text-sm">
                    Jln. Yoka - Arso, Kampung Yoka, Distrik Heram, Kota Jayapura
                    - Papua
                </p>
                <p class="text-sm">Kode Pos : 99531, No Telp: 081248227115</p>
                <p class="text-sm">Email: puskesmayoka@gmail.com</p>
            </div>
            <!-- Logo Puskesmas -->
            <LogoPuskesmas class="w-16 h-16" />
        </div>

        <!-- Detail Pasien -->
        <div class="text-left mb-6">
            <h1
                class="text-2xl text-center font-semibold text-gray-800 uppercase"
            >
                Detail Rekam Medik
            </h1>
        </div>

        <!-- Kondisi Pasien -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Data Umum
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 mt-3">
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Nama Pasien</h4>
                    <p class="text-gray-500">
                        {{ props.rekammedik[0].pasien.nama }}
                    </p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Umur</h4>
                    <p class="text-gray-500">
                        {{
                            calculateAge(
                                props.rekammedik[0].pasien.tanggal_lahir
                            )
                        }}
                        Tahun
                    </p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Nomor Rekam Medik</h4>
                    <p class="text-gray-500">
                        {{ props.rekammedik[0].pasien.rekammedik }}
                    </p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Tanggal Berobat</h4>
                    <p class="text-gray-500">
                        {{ getDate(props.rekammedik[0].tanggal) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Kondisi Pasien -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Kondisi Pasien
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-3">
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Berat Badan</h4>
                    <p class="text-gray-500">{{ kondisi.berat }} kg</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Tinggi Badan</h4>
                    <p class="text-gray-500">{{ kondisi.tinggi }} cm</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Lingkar Badan</h4>
                    <p class="text-gray-500">{{ kondisi.lingkar_badan }} cm</p>
                </div>
                <div class="p-4 bg-gray-100 rounded-lg shadow">
                    <h4 class="font-medium text-gray-700">Tekanan Darah</h4>
                    <p class="text-gray-500">
                        {{ kondisi.tekanan_darah }} mmHg
                    </p>
                </div>
            </div>
        </div>

        <!-- Keluhan Pasien -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Keluhan Pasien
            </h3>
            <ul class="mt-3">
                <li
                    v-for="(item, index) in keluhan"
                    :key="index"
                    class="p-3 bg-gray-100 rounded-lg shadow mb-2"
                >
                    {{ index + 1 }}. {{ item.value }}
                </li>
            </ul>
            <p v-if="keluhan.length === 0" class="text-gray-500">
                Tidak ada keluhan yang tercatat.
            </p>
        </div>

        <!-- Penanganan -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Penanganan
            </h3>
            <ul class="mt-3">
                <li
                    v-for="(item, index) in penanganan"
                    :key="index"
                    class="p-3 bg-gray-100 rounded-lg shadow mb-2"
                >
                    {{ index + 1 }}. {{ item.value }}
                </li>
            </ul>
            <p v-if="penanganan.length === 0" class="text-gray-500">
                Tidak ada penanganan yang tercatat.
            </p>
        </div>

        <!-- Resep Obat -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Resep Obat
            </h3>
            <div
                v-if="resep.length > 0"
                class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3"
            >
                <div
                    v-for="(item, index) in resep"
                    :key="index"
                    class="p-4 bg-gray-100 rounded-lg shadow"
                >
                    <h4 class="font-medium text-gray-700">
                        Obat: {{ item.obat_id }}
                    </h4>
                    <p class="text-gray-600">Dosis: {{ item.dosis }}</p>
                    <p class="text-gray-600">Catatan: {{ item.catatan }}</p>
                </div>
            </div>
            <p v-else class="text-gray-500">Tidak ada resep yang tercatat.</p>
        </div>

        <!-- Resep Manual -->
        <div class="mb-6" v-if="props.rekammedik[0].resep_manual">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Resep Manual
            </h3>
            <p class="p-3 bg-gray-100 rounded-lg shadow mt-3">
                {{ props.rekammedik[0].resep_manual }}
            </p>
        </div>

        <!-- Hasil Lab -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-black border-b pb-2">
                Hasil Laboratorium
            </h3>
            <div v-if="props.rekammedik[0].hasil_lab" class="mt-3">
                <img
                    :src="props.rekammedik[0].hasil_lab"
                    :alt="'Hasil lab untuk ' + props.rekammedik[0].pasien.nama"
                    class="rounded-lg shadow-md w-full"
                />
            </div>
            <p v-else class="text-gray-500">
                Data hasil laboratorium belum tersedia.
            </p>
        </div>
    </div>
</template>

<style scoped>
/* Hide content by default */
.print-header,
.print-info {
    display: none;
}

/* Display content only in print mode */
@media print {
    .print-header {
        display: flex;
    }

    .print-info {
        display: block;
    }

    /* Adjust page content for print */
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .print-info {
        margin-top: 20px;
    }

    /* Remove non-essential elements */
    .noprint {
        display: none;
    }

    /* Prevent page breaks within content */
    .no-break {
        page-break-inside: avoid;
    }

    /* Adjust layout for print */
    #print-resep {
        padding: 20px;
    }

    /* Add margins for the printed content */
    .grid {
        margin: 0;
        padding: 0;
    }
}
</style>
