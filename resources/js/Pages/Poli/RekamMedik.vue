<script setup>
import { ref, computed } from "vue";
import Swal from "sweetalert2";
import Search from "@/Components/Search.vue";
import PoliLayout from "@/Layouts/PoliLayout.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";
import Check from "@/Icons/Check.vue";

const props = defineProps({
    poli: Array,
    pegawai: Array,
    data: Object,
});

// Generate tahun secara dinamis
const years = computed(() => {
    const currentYear = new Date().getFullYear();
    const startYear = 2024; // Ganti tahun awal jika diperlukan
    return Array.from(
        { length: currentYear - startYear + 1 },
        (_, i) => startYear + i
    );
});

// State
const searchTerm = ref("");
const selectedMonth = ref("");
const selectedYear = ref("");

// Filter data berdasarkan input
const filteredData = computed(() => {
    let filtered = props.data.data || [];

    if (searchTerm.value) {
        filtered = filtered.filter(
            (item) =>
                item.pasien.nama
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) ||
                item.pasien.rekammedik
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase())
        );
    }

    if (selectedYear.value) {
        filtered = filtered.filter((item) => {
            const itemYear = new Date(item.tanggal).getFullYear();
            return itemYear === parseInt(selectedYear.value);
        });
    }

    if (selectedMonth.value) {
        filtered = filtered.filter((item) => {
            const itemMonth = (
                "0" +
                (new Date(item.tanggal).getMonth() + 1)
            ).slice(-2);
            return itemMonth === selectedMonth.value;
        });
    }

    return filtered;
});

// Fungsi hapus item
function deleteItem(item) {
    Swal.fire({
        title: "Anda Yakin?",
        text: "Menghapus Data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("poli.rekammedik.delete", item.id), {
                onSuccess: () => {
                    Swal.fire("Deleted!", "Data Berhasil Dihapus.", "success");
                    props.data.data = props.data.data.filter(
                        (i) => i.id !== item.id
                    );
                },
                onError: (err) => {
                    Swal.fire(
                        "Error",
                        err.message || "Gagal menghapus data.",
                        "error"
                    );
                },
            });
        }
    });
}

// Fungsi untuk navigasi pagination
const paginate = (url) => {
    if (url) {
        window.location.href = url;
    }
};

// Fungsi untuk menangani pencarian
const onSearchText = (text) => {
    searchTerm.value = text;
};
</script>

<template>
    <PoliLayout :poli="props.poli" :pegawai="props.pegawai">
        <div class="mt-5">
            <h1 class="text-xl font-semibold text-gray-700">
                DATA REKAM MEDIK
            </h1>
            <div class="flex justify-end items-center my-4">
                <!-- <div class="flex items-center">
                    <select
                        v-model="selectedMonth"
                        id="month"
                        class="mx-2 rounded-lg bg-transparent text-neutral-700"
                    >
                        <option value="">Bulan</option>
                        <option
                            v-for="month in 12"
                            :key="month"
                            :value="String(month).padStart(2, '0')"
                        >
                            {{
                                new Date(0, month - 1).toLocaleString("id-ID", {
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
                </div> -->
                <Search @on-search="onSearchText" />
            </div>
        </div>

        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Kode Rekam Medik
                            </th>
                            <th
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Pasien
                            </th>
                            <th
                                class="text-center border-b border-gray-200 px-5 py-3 text-sm font-normal uppercase text-neutral-500"
                            >
                                Antrian
                            </th>
                            <th
                                class="text-center border-b border-gray-200 px-5 py-3 text-sm font-normal uppercase text-neutral-500"
                            >
                                Jumlah
                            </th>
                            <th
                                class="w-20 border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="filteredData.length"
                            v-for="item in filteredData"
                            :key="item.id"
                        >
                            <td
                                class="border-b whitespace-nowrap border-gray-200 p-3 text-sm"
                            >
                                {{ item.pasien.rekammedik }}
                            </td>

                            <td
                                class="border-b whitespace-nowrap border-gray-200 p-3 text-sm"
                            >
                                {{ item.pasien.nama }}
                            </td>

                            <td
                                class="border-b border-gray-200 p-3 text-sm text-center"
                            >
                                <span
                                    v-if="item.total_status_baru > 0"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-red-500 rounded-full"
                                >
                                    <span
                                        >Ada
                                        {{ item.total_status_baru }}
                                        Antrian</span
                                    >
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-green-500 rounded-full"
                                >
                                    <Check /> Selesai
                                </span>
                            </td>

                            <td
                                class="border-b border-gray-200 p-3 text-sm text-center"
                            >
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-green-500 rounded-full"
                                >
                                    <span>{{ item.total_rekam_medik }}</span>
                                </span>
                            </td>

                            <td
                                class="border-l border-gray-200 gap-2 p-4 text-sm flex"
                            >
                                <a
                                    :href="
                                        '/poli/rekammedik/pasien/' +
                                        item.pasien_id
                                    "
                                    class="text-cyan-500 hover:text-cyan-700"
                                >
                                    <DetailListIcon class="w-5" />
                                </a>
                            </td>
                        </tr>

                        <tr v-else>
                            <td
                                colspan="5"
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
                                v-if="link.url && filteredData.length > 0"
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
    </PoliLayout>
</template>
