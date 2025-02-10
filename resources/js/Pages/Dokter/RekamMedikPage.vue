<script setup>
import { ref, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import DokterLayout from "@/Layouts/DokterLayout.vue";
import Search from "@/Components/Search.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";
import Check from "@/Icons/Check.vue";

const props = defineProps({
    poli: Array,
    dokter: Array,
    data: Object,
});

const searchTerm = ref("");

// **Perbaikan search function**
const searchRekammedik = computed(() => {
    if (!props.data || !props.data.data) return [];

    return props.data.data.filter(
        (item) =>
            item.pasien.nama
                .toLowerCase()
                .includes(searchTerm.value.toLowerCase()) ||
            item.pasien.rekammedik
                .toLowerCase()
                .includes(searchTerm.value.toLowerCase())
    );
});

// **Fungsi Paginasi Inertia.js**
const paginate = (url) => {
    if (url) {
        router.get(url); // Gunakan Inertia.js untuk navigasi
    }
};
</script>

<template>
    <Head title="Rekam Medik" />
    <DokterLayout :poli="poli" :dokter="dokter">
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">DATA REKAM MEDIK</h1>

            <Search v-on:on-search="(text) => (searchTerm = text)"></Search>
        </div>

        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="border-b px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Kode Rekam Medik
                            </th>
                            <th
                                class="border-b px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Pasien
                            </th>
                            <th
                                class="text-center border-b px-5 py-3 text-sm font-normal uppercase text-neutral-500"
                            >
                                Periksa
                            </th>
                            <th
                                class="text-center border-b px-5 py-3 text-sm font-normal uppercase text-neutral-500"
                            >
                                Jumlah
                            </th>
                            <th
                                class="border-b px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="searchRekammedik.length"
                            v-for="item in searchRekammedik"
                            :key="item.id"
                        >
                            <td class="border-b p-3 text-sm">
                                {{ item.pasien.rekammedik }}
                            </td>

                            <td class="border-b p-3 text-sm">
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

                            <td class="border-b p-3 text-sm flex space-x-2">
                                <a
                                    :href="
                                        '/dokter/rekammedik/pasien/' +
                                        item.pasien_id
                                    "
                                    class="text-blue-500 hover:text-blue-700"
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

            <!-- Pagination -->
            <div class="flex justify-center mt-4">
                <nav>
                    <ul class="flex items-center space-x-2">
                        <li v-if="data.first_page_url">
                            <button
                                @click.prevent="paginate(data.first_page_url)"
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                First
                            </button>
                        </li>
                        <li v-if="data.prev_page_url">
                            <button
                                @click.prevent="paginate(data.prev_page_url)"
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                Prev
                            </button>
                        </li>
                        <li
                            v-for="(link, index) in data.links.filter(
                                (link) => !isNaN(link.label)
                            )"
                            :key="index"
                        >
                            <button
                                v-if="link.url"
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
                        <li v-if="data.next_page_url">
                            <button
                                @click.prevent="paginate(data.next_page_url)"
                                class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300"
                            >
                                Next
                            </button>
                        </li>
                        <li v-if="data.last_page_url">
                            <button
                                @click.prevent="paginate(data.last_page_url)"
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
</template>
