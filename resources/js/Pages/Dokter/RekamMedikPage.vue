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

const props = defineProps({
    poli: Array,
    dokter: Array,
    data: Array,
});

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

const onChangeSearch = (text) => {
    searchTerm.value = text;
};

const searchTerm = ref("");

const searchRekammedik = computed(() => {
    const data = props.data.data || [];

    if (searchTerm.value === "") {
        return data;
    }

    let matches = 0;
    return data.filter((item) => {
        if (
            item.pasien.nama
                .toLowerCase()
                .includes(searchTerm.value.toLowerCase()) &&
            matches < 10
        ) {
            matches++;
            return item;
        }
    });
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
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">DATA REKAM MEDIK</h1>
            <Search v-on:on-search="onChangeSearch"></Search>
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
</template>
