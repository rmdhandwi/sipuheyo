<script setup>
import Layout from "@/dashboard/Layout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Search from "@/Components/Search.vue";
import AddIcon from "@/Icons/AddIcon.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";
import Info from "@/Icons/Info.vue";

const props = defineProps({
    data: Array,
    polis: Array,
});

function addNewItem() {
    window.location = "/admin/rekammedik/add";
}

// State for search and filters
const searchTerm = ref("");

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
                (item.pasien.rekammedik
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) &&
                    matches < 10)
        );
    }

    return filteredData;
});

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
        </div>

        <div class="flex justify-between items-center my-2">
            <Search
                v-on:on-search="onSearchText"
                placeholder="Cari nama pasien..."
            />
            <AddIcon
                class="cursor-pointer text-teal-500 w-12"
                @click="addNewItem"
            ></AddIcon>
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
                                {{ item.pasien.rekammedik }}
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                {{ item.pasien.nama }}
                            </td>
                            <td
                                class="border-b border-gray-200 p-3 text-sm text-center"
                            >
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-1 text-sm font-semibold text-white bg-red-500 rounded-full"
                                >
                                    <span>{{ item.total_status_baru }}</span>
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
                                class="border-l border-gray-200 p-3 text-sm flex gap-2"
                            >
                                <a
                                    :href="
                                        '/admin/rekammedik/pasien/' +
                                        item.pasien.id
                                    "
                                    class="text-blue-500 hover:text-blue-700"
                                >
                                    <DetailListIcon class="w-5" />
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
</template>
