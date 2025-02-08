<script setup>
import DokterLayout from "@/Layouts/DokterLayout.vue";
import { ref, computed, watch } from "vue";
import DatePicker from "@/Components/DatePicker.vue";
import PrinterIcon from "@/Icons/PrinterIcon.vue";
import Search from "@/Components/Search.vue";
import LogoKota from "@/Icons/LogoKota.vue";
import LogoPuskesmas from "@/Icons/LogoPuskesmas.vue";
import { Head } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const props = defineProps({
    dokter: Array,
    poli: Array,
    data: Array,
});

// Fungsi untuk memformat tanggal dan waktu secara terpisah
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        day: "2-digit",
        month: "long",
        year: "numeric",
    });
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });
}

const searchTerm = ref("");
const selectedMonth = ref("");
const selectedYear = ref(new Date().getFullYear().toString());

// Ambil data yang difilter berdasarkan bulan dan tahun
const filteredData = computed(() => {
    const data = props.data.data || [];

    // Filter data berdasarkan bulan dan tahun
    return data.filter((item) => {
        const date = new Date(item.konsultasi_berikut);
        const itemMonth = (date.getMonth() + 1).toString(); // Bulan dalam 1-12
        const itemYear = date.getFullYear().toString();

        // Cek apakah bulan dan tahun sesuai
        const matchMonth =
            selectedMonth.value === "" || selectedMonth.value === itemMonth;
        const matchYear =
            selectedYear.value === "" || selectedYear.value === itemYear;

        // Hanya return item jika bulan dan tahun cocok
        return matchMonth && matchYear;
    });
});

// Cari data berdasarkan nama pasien
const searchJadwal = computed(() => {
    const data = filteredData.value;

    if (searchTerm.value === "") {
        return data;
    }

    return data.filter((item) =>
        item.pasien.nama.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const onChangeSearch = (text) => {
    searchTerm.value = text;
};

// Fungsi navigasi pagination
const paginate = (url) => {
    if (url) {
        window.location.href = url;
    }
};

// Fungsi cetak laporan
const printReport = () => {
    if (searchJadwal.value.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "Tidak ada data yang bisa dicetak!",
        });
        return;
    }
    window.print();
};

const months = [
    { label: "Januari", value: "1" },
    { label: "Februari", value: "2" },
    { label: "Maret", value: "3" },
    { label: "April", value: "4" },
    { label: "Mei", value: "5" },
    { label: "Juni", value: "6" },
    { label: "Juli", value: "7" },
    { label: "Agustus", value: "8" },
    { label: "September", value: "9" },
    { label: "Oktober", value: "10" },
    { label: "November", value: "11" },
    { label: "Desember", value: "12" },
];
</script>

<template>
    <Head title="Konsultasi Berikut" />
    <DokterLayout class="noprint" :poli="props.poli">
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">LAPORAN KUNJUNGAN BEROBAT BERIKUT</h1>
            <div class="flex">
                <PrinterIcon
                    class="cursor-pointer text-amber-600 w-12"
                    @click="printReport()"
                ></PrinterIcon>
            </div>
        </div>
        <div class="flex justify-between items-center mt-3">
            <div class="flex items-center gap-4">
                <select
                    v-model="selectedMonth"
                    class="border p-2 w-[150px] rounded-md"
                >
                    <option value="">Bulan</option>
                    <option
                        v-for="month in months"
                        :key="month.value"
                        :value="month.value"
                    >
                        {{ month.label }}
                    </option>
                </select>
                <!-- <select v-model="selectedYear" class="border w-[150px] p-2 rounded-md">
                    <option value="">Tahun</option>
                    <option
                        v-for="year in 5"
                        :key="year"
                        :value="new Date().getFullYear() - (5 - year)"
                    >
                        {{ new Date().getFullYear() - (5 - year) }}
                    </option>
                </select> -->
            </div>
            <Search v-on:on-search="onChangeSearch"></Search>
        </div>

        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Tanggal Kunjungan
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Waktu Kunjungan
                            </th>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Pasien
                            </th>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Poli
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Dokter
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="searchJadwal.length"
                            v-for="item in searchJadwal"
                            :key="item.id"
                        >
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ formatDate(item.konsultasi_berikut) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ formatTime(item.konsultasi_berikut) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.pasien.nama }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.poli.nama }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.dokter.nama }}
                                </p>
                            </td>
                        </tr>
                        <tr v-else>
                            <td
                                colspan="5"
                                class="text-gray-400 text-center py-4"
                            >
                                Data Jadwal Konsultasi Tidak Ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Custom Pagination -->
            <div class="flex justify-center mt-4">
                <nav>
                    <ul class="flex items-center space-x-2">
                        <li v-if="props.data.first_page_url">
                            <button
                                @click.prevent="
                                    paginate(props.data.first_page_url)
                                "
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                First
                            </button>
                        </li>
                        <li v-if="props.data.prev_page_url">
                            <button
                                @click.prevent="
                                    paginate(props.data.prev_page_url)
                                "
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                Prev
                            </button>
                        </li>

                        <li
                            v-for="(link, index) in props.data.links.filter(
                                (link) => !isNaN(link.label)
                            )"
                            :key="index"
                        >
                            <button
                                v-if="link.url && searchJadwal.length > 0"
                                @click.prevent="paginate(link.url)"
                                :class="{
                                    'px-4 py-2 bg-blue-500 text-white rounded-lg':
                                        link.active,
                                    'px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300':
                                        !link.active,
                                }"
                            >
                                {{ link.label }}
                            </button>
                        </li>

                        <li v-if="props.data.next_page_url">
                            <button
                                @click.prevent="
                                    paginate(props.data.next_page_url)
                                "
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                Next
                            </button>
                        </li>

                        <li v-if="props.data.last_page_url">
                            <button
                                @click.prevent="
                                    paginate(props.data.last_page_url)
                                "
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                Last
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </DokterLayout>

    <div class="print">
        <div>
            <div class="w-full flex justify-between border-b-2 border-gray-900">
                <LogoKota class="w-16 h-16"></LogoKota>
                <div class="text-center">
                    <h2>PEMERINTAH KOTA JAYAPURA</h2>
                    <h2>DINAS KESEHATAN</h2>
                    <h2>PUSKESMAS HEBEYBHULU</h2>
                    <div class="text-sm">
                        Jln. Yoka - Arso , Kampung Yoka, DIstrik Heram, Kota
                        Jayapura - Papua
                    </div>
                    <div class="text-sm">
                        Kode Pos : 99531, NO Telp 081248227115
                    </div>
                    <div class="text-sm">email : puskesmayoka@gmail.com</div>
                </div>
                <LogoPuskesmas class="w-16 h-16"></LogoPuskesmas>
            </div>
            <hr />

            <div>
                <div class="flex">
                    <div class="w-32">Nama Poli</div>
                    <div>: {{ props.poli.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-32">Penyakit</div>
                    <div>: {{ props.poli.penyakit }}</div>
                </div>
                <div class="flex">
                    <div class="w-32">Tanggal</div>
                    <div>: {{ new Date().toLocaleDateString() }}</div>
                </div>
            </div>

            <table class="w-full mt-3">
                <thead>
                    <tr>
                        <th>Tanggal Kunjungan</th>
                        <th>Waktu Kunjungan</th>
                        <th>Pasien</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in searchJadwal">
                        <td>
                            <p class="whitespace-nowrap">
                                {{ formatDate(item.konsultasi_berikut) }}
                            </p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">
                                {{ formatTime(item.konsultasi_berikut) }}
                            </p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">
                                {{ item.pasien.nama }}
                            </p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">
                                {{ item.poli.nama }}
                            </p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">
                                {{ item.dokter.nama }}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
