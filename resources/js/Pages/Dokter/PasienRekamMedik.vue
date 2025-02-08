<script setup>
import DokterLayout from "@/Layouts/DokterLayout.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Swal from "sweetalert2";
import Check from "@/Icons/Check.vue";
import Info from "@/Icons/Info.vue";
import Panding from "@/Icons/Panding.vue";
import Wrong from "@/Icons/Wrong.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";
import { ref, computed } from "vue";
import Search from "@/Components/Search.vue";
import { Head } from "@inertiajs/vue3";
import Helper from "@/heper";
import BackIcon from "@/Icons/BackIcon.vue";
import PrinterIcon from "@/Icons/PrinterIcon.vue";
import LogoKota from "@/Icons/LogoKota.vue";
import LogoPuskesmas from "@/Icons/LogoPuskesmas.vue";

const props = defineProps({
    poli: Array,
    dokter: Array,
    data: Array,
    rekammedik: Object,
});

function backAction() {
    window.location = "/dokter/rekammedik";
}

function deleteItem(item) {
    Swal.fire({
        title: "Anda Yakin ?",
        text: "Menghapus Data !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("admin.rekammedik.delete", item.id), {
                onSuccess: (res) => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di hapus.",
                        icon: "success",
                    });
                },
                onError: (err) => {
                    Swal.fire({
                        title: "Error",
                        text: err,
                        icon: "error",
                    });
                },
            });
        }
    });
}

function getDate(dateString) {
    const date = new Date(dateString);

    // Mengambil Tahun, Bulan, dan Tanggal
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);

    return `${day}/${month}/${year}`;
}

const searchTerm = ref("");
const selectedMonth = ref("");
const selectedYear = ref("");

const onChangeSearch = (text) => {
    searchTerm.value = text;
};

const searchRekammedik = computed(() => {
    let filteredData = props.data.data || [];
    let matches = 0;

    if (searchTerm.value) {
        filteredData = filteredData.filter(
            (item) =>
                (item.pasien.nama
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10) ||
                (item.tanggal
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10) ||
                (item.poli.penyakit
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10) ||
                (item.status
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10)
        );
    }

    // Filter by selected Month
    if (selectedMonth.value) {
        filteredData = filteredData.filter((item) => {
            const itemMonth = new Date(item.tanggal).getMonth() + 1; // Get month as 1-based index
            return itemMonth == selectedMonth.value;
        });
    }

    if (selectedYear.value) {
        filteredData = filteredData.filter((item) => {
            const itemYear = new Date(item.tanggal).getFullYear();
            return itemYear === parseInt(selectedYear.value);
        });
    }

    return filteredData;
});

function printReport() {
    if (searchRekammedik.value.length === 0) {
        // SweetAlert jika tidak ada data untuk dicetak
        Swal.fire({
            title: "Tidak ada data",
            text: "Tidak ada data yang tersedia untuk dicetak.",
            icon: "error",
            confirmButtonText: "OK",
        });
        return;
    }

    // Ambil elemen dengan class "print"
    const printElement = document.querySelector(".print");
    if (!printElement) {
        console.error("Elemen dengan class 'print' tidak ditemukan.");
        return;
    }

    // Simpan konten asli halaman
    const originalContents = document.body.innerHTML;

    // Ganti konten halaman dengan elemen "print"
    document.body.innerHTML = printElement.outerHTML;

    // Tambahkan judul "Data Rekam Medik" sesuai filter
    const titleElement = document.createElement("h3");
    titleElement.textContent = `Data Rekam Medik ${
        selectedMonth.value
            ? `Bulan: ${new Date(0, selectedMonth.value - 1).toLocaleString(
                  "default",
                  { month: "long" }
              )}`
            : ""
    }`;
    titleElement.classList.add("text-center", "font-bold", "mb-4");
    printElement.prepend(titleElement);

    // Cetak halaman
    window.print();

    // Kembalikan konten asli halaman
    document.body.innerHTML = originalContents;
    location.reload();
}

const years = computed(() => {
    const currentYear = new Date().getFullYear();
    const startYear = 2024; // Ganti tahun awal jika diperlukan
    return Array.from(
        { length: currentYear - startYear + 1 },
        (_, i) => startYear + i
    );
});

// Fungsi untuk navigasi pagination
const paginate = (url) => {
    if (url) {
        window.location.href = url;
    }
};
</script>

<template>
    <Head title="Rekam Medik" />
    <DokterLayout :poli="props.poli" :dokter="props.dokter">
        <div class="mt-5 flex justify-between items-center">
            <div>
                <h1 class="text-xl">DATA REKAM MEDIK</h1>
                <h1 class="text-xl">
                    {{ props.rekammedik.pasien.rekammedik }}
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="backAction">
                    <BackIcon class="cursor-pointer w-10" />
                </button>
                <PrinterIcon
                    class="cursor-pointer text-amber-600 w-12"
                    @click="printReport"
                />
            </div>
        </div>
        <div class="flex justify-between items-center my-4">
            <div class="flex items-center">
                <select
                    v-model="selectedMonth"
                    class="mx-2 rounded-lg bg-transparent text-neutral-700"
                >
                    <option value="">Bulan</option>
                    <option v-for="month in 12" :key="month" :value="month">
                        {{
                            new Date(0, month - 1).toLocaleString("default", {
                                month: "long",
                            })
                        }}
                    </option>
                </select>

                <select
                    v-model="selectedYear"
                    id="year"
                    class="mx-2 rounded-lg bg-transparent text-neutral-700"
                >
                    <option value="">Tahun</option>
                    <option v-for="year in years" :key="year" :value="year">
                        {{ year }}
                    </option>
                </select>
            </div>

            <Search
                v-on:on-search="onChangeSearch"
                placeholder="Cari nama pasien..."
            />
        </div>
        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Antrian
                            </th>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Tanggal
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
                                Penyakit
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Status
                            </th>
                            <th
                                scope="col"
                                class="w-20 border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="searchRekammedik.length"
                            v-for="item in searchRekammedik"
                        >
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.antrian }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ getDate(item.tanggal) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.pasien.nama }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.poli.penyakit }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <span
                                    v-if="item.status === 'Dokter'"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-green-500 rounded-full"
                                >
                                    <Check />
                                    <span>{{ item.status }}</span>
                                </span>
                                <span
                                    v-if="item.status === 'Baru'"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-blue-500 rounded-full"
                                >
                                    <Info />
                                    <span>{{ item.status }}</span>
                                </span>
                                <span
                                    v-if="item.status === 'Poli'"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-black bg-yellow-500 rounded-full"
                                >
                                    <Panding />
                                    <span>{{ item.status }}</span>
                                </span>
                                <span
                                    v-if="item.status === 'Batal'"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-red-500 rounded-full"
                                >
                                    <Wrong />
                                    <span>{{ item.status }}</span>
                                </span>
                            </td>

                            <td
                                class="border-b border-gray-200 p-3 text-sm flex"
                            >
                                <a
                                    v-if="item.status === 'Dokter'"
                                    :href="
                                        '/dokter/rekammedik/detail/' + item.id
                                    "
                                    class="text-blue-500 hover:text-blue-700"
                                >
                                    <DetailListIcon class="w-5" />
                                </a>
                                <a
                                    v-else-if="item.status === 'Poli'"
                                    :href="'/dokter/rekammedik/' + item.id"
                                    class="text-amber-500 hover:text-amber-700"
                                >
                                    <EditIcon class="w-5" />
                                </a>
                                <a
                                    v-else
                                    @click="deleteItem(item)"
                                    class="cursor-pointer text-rose-600 hover:text-rose-900"
                                >
                                    <DeleteIcon class="w-5" />
                                </a>
                            </td>
                        </tr>
                        <tr v-else>
                            <td
                                colspan="8"
                                class="text-gray-400 text-center py-4"
                            >
                                Data Rekam Medik Tidak Ditemukan
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
                                v-if="link.url && searchRekammedik.length > 0"
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

            <div class="flex flex-col justify-center items-center">
                <h3 class="text-center text-2xl font-bold mb-2 mt-2">
                    Data Rekam Medik
                </h3>
                <h1 class="text-center text-xl font-bold mb-2">
                    {{ props.rekammedik.poli.nama }}
                </h1>
                <h1 class="text-center text-xl font-bold mb-2">
                    {{ props.rekammedik.pasien.rekammedik }}
                </h1>
            </div>

            <div class="mb-2">
                <span class="text-lg" v-if="selectedMonth">
                    Bulan :
                    {{
                        new Date(0, selectedMonth - 1).toLocaleString(
                            "default",
                            {
                                month: "long",
                            }
                        )
                    }}
                </span>
            </div>

            <table class="w-full mt-3 border-collapse border border-gray-300">
                <thead class="text-left">
                    <tr>
                        <th class="border border-gray-300 p-2">Kode Antrian</th>
                        <th class="border border-gray-300 p-2">Tanggal</th>
                        <th class="border border-gray-300 p-2">Pasien</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in searchRekammedik" :key="item.id">
                        <td class="border border-gray-300 p-2">
                            {{ item.antrian }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ getDate(item.tanggal) }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ item.pasien.nama }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
