<script setup>
import LogoKota from "@/Icons/LogoKota.vue";
import LogoPuskesmas from "@/Icons/LogoPuskesmas.vue";
import Layout from "@/dashboard/Layout.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Swal from "sweetalert2";
import { useForm, Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Search from "@/Components/Search.vue";
import AddIcon from "@/Icons/AddIcon.vue";
import PrinterIcon from "@/Icons/PrinterIcon.vue";
import Check from "@/Icons/Check.vue";
import Info from "@/Icons/Info.vue";
import Panding from "@/Icons/Panding.vue";
import Wrong from "@/Icons/Wrong.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";

const props = defineProps({
    data: {
        type: Array,
    },
    polis: {
        type: Array,
    },
});

function addNewItem() {
    window.location = "/admin/rekammedik/add";
}

function getDate(dateString) {
    const date = new Date(dateString);

    // Mengambil Tahun, Bulan, dan Tanggal
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);

    return `${day}/${month}/${year}`;
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
            router.delete(route("admin.rekammedik.delete", item.id), {
                onSuccess: (res) => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di hapus.",
                        icon: "success",
                    });
                    let index = data.rekamMedik.indexOf(item);
                    data.rekamMedik.splice(index, 1);
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

const years = computed(() => {
    const currentYear = new Date().getFullYear();
    const startYear = 2024; // Ganti tahun awal jika diperlukan
    return Array.from(
        { length: currentYear - startYear + 1 },
        (_, i) => startYear + i
    );
});

// State for search and filters
const searchTerm = ref("");
const selectedPoli = ref("");
const selectedMonth = ref("");
const selectedYear = ref("");

const onSearchText = (text) => {
    searchTerm.value = text;
};

const filterData = computed(() => {
    let filteredData = props.data.data || [];
    let matches = 0;

    // Filter by search term
    if (searchTerm.value) {
        filteredData = filteredData.filter(
            (item) =>
                (item.pasien.nama
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10) ||
                (item.poli.nama
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10) ||
                (item.status
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10)
        );
    }

    // Filter by selected Poli
    if (selectedPoli.value) {
        filteredData = filteredData.filter(
            (item) => item.poli_id == selectedPoli.value
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
    if (filterData.value.length === 0) {
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
        selectedPoli.value
            ? `Poli: ${
                  props.polis.find((p) => p.id == selectedPoli.value)?.nama ||
                  ""
              }`
            : ""
    } ${
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

function paginate(url) {
    if (url) {
        window.location.href = url;
    }
}
</script>

<template>
    <Head title="Rekam Medik" />
    <Layout>
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">DATA REKAM MEDIK</h1>
            <div class="flex">
                <AddIcon
                    class="cursor-pointer text-teal-500 w-12"
                    @click="addNewItem"
                ></AddIcon>
                <PrinterIcon
                    class="cursor-pointer text-amber-600 w-12"
                    @click="printReport"
                ></PrinterIcon>
            </div>
        </div>

        <div class="flex justify-between items-center my-4">
            <div class="flex items-center">
                <select
                    v-model="selectedPoli"
                    class="mx-2 rounded-lg bg-transparent text-neutral-700"
                >
                    <option value="">Poli</option>
                    <option
                        v-for="item in props.polis"
                        :key="item.id"
                        :value="item.id"
                    >
                        {{ item.nama }}
                    </option>
                </select>

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
                v-on:on-search="onSearchText"
                placeholder="Cari nama pasien..."
            />
        </div>

        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Kode
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Tanggal
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Pasien
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Poli
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Dokter
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Status
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="filterData.length"
                            v-for="item in filterData"
                            :key="item.id"
                        >
                            <td class="border-b border-gray-200 p-3 text-sm">
                                {{ item.antrian }}
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                {{ getDate(item.tanggal) }}
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                {{ item.pasien.nama }}
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                {{ item.poli.nama }}
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                {{ item.dokter.nama }}
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
                                class="border-l border-gray-200 p-3 text-sm flex gap-2"
                            >
                                <a
                                    v-if="item.status !== 'Baru'"
                                    :href="
                                        '/admin/rekammedik/detail/' + item.id
                                    "
                                    class="text-blue-500 hover:text-blue-700"
                                >
                                    <DetailListIcon class="w-5" />
                                </a>
                                <a
                                    @click="deleteItem(item)"
                                    class="cursor-pointer text-rose-600 hover:text-rose-900"
                                >
                                    <DeleteIcon class="w-5" />
                                </a>
                            </td>
                        </tr>
                        <tr v-else>
                            <td
                                colspan="7"
                                class="text-center p-3 text-gray-400"
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
                        <!-- First Page Button -->
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
                        <!-- Previous Page Button -->
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

                        <!-- Page Links -->
                        <li
                            v-for="(link, index) in props.data.links.filter(
                                (link) => !isNaN(link.label)
                            )"
                            :key="index"
                        >
                            <button
                                v-if="link.url && filterData.length"
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

                        <!-- Next Page Button -->
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

                        <!-- Last Page Button -->
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
    </Layout>

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

            <!-- Judul tambahan dinamis -->
            <h3 class="text-center font-bold mb-4">
                Data Rekam Medik
                <span v-if="selectedPoli">
                    Poli:
                    {{
                        props.polis.find((p) => p.id == selectedPoli)?.nama
                    }}</span
                >
                <span v-if="selectedMonth">
                    Bulan:
                    {{
                        new Date(0, selectedMonth - 1).toLocaleString(
                            "default",
                            {
                                month: "long",
                            }
                        )
                    }}
                </span>
            </h3>

            <table class="w-full mt-3 border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 p-2">Kode Antrian</th>
                        <th class="border border-gray-300 p-2">Tanggal</th>
                        <th class="border border-gray-300 p-2">Pasien</th>
                        <th class="border border-gray-300 p-2">Poli</th>
                        <th class="border border-gray-300 p-2">Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filterData" :key="item.id">
                        <td class="border border-gray-300 p-2">
                            {{ item.antrian }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ getDate(item.tanggal) }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ item.pasien.nama }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ item.poli.nama }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ item.dokter.nama }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
