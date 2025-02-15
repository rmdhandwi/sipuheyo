<script setup>
import Layout from "@/dashboard/Layout.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Swal from "sweetalert2";
import { useForm, Head } from "@inertiajs/vue3";
import AddIcon from "@/Icons/AddIcon.vue";
import Search from "@/Components/Search.vue";
import { ref, computed } from "vue";

// Define props with type checking
const props = defineProps({
    data: {
        type: Array,
    },
});

const form = useForm({
    id: 0,
});

// Search functionality
const searchTerm = ref("");
const onSearchText = (text) => {
    searchTerm.value = text;
};

const filterDataPegawai = computed(() => {
    const data = props.data?.data || [];

    if (searchTerm.value === "") {
        return data;
    }

    let matches = 0;
    const searchTermLower = searchTerm.value.toLowerCase();

    return data.filter((item) => {
        if (
            matches < 10 &&
            (item.nama.toLowerCase().includes(searchTermLower) ||
                item.nip.toLowerCase().includes(searchTermLower))
        ) {
            matches++;
            return item;
        }
    });
});

// Add a new item
function addNewItem() {
    console.log("siap");
    window.location = "/admin/pegawai/add";
}

// Delete an item
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
            form.delete(route("admin.pegawai.delete", item.id), {
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

// Handle pagination
const paginate = (url) => {
    if (url) {
        window.location.href = url; // Navigate to the new page URL for pagination
    }
};
</script>

<template>
    <Head title="Pegawai" />
    <Layout>
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">DATA PEGAWAI</h1>
            <AddIcon
                class="cursor-pointer text-teal-500 w-12"
                @click="addNewItem()"
            ></AddIcon>
        </div>
        <div>
            <Search v-on:on-search="onSearchText"></Search>
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
                                NIP
                            </th>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Nama Pegawai
                            </th>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Jenis Kelamin
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Bagian
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Email
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Kontak
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
                            v-if="filterDataPegawai.length"
                            v-for="item in filterDataPegawai"
                            :key="item.id"
                        >
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.nip }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.jk }}</p>
                            </td>

                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.bagian }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.email }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.kontak }}
                                </p>
                            </td>
                            <td
                                class="border-l border-gray-200 p-3 text-sm flex"
                            >
                                <a
                                    :href="'/admin/pegawai/add/' + item.id"
                                    class="text-amber-500 hover:text-amber-700"
                                >
                                    <EditIcon class="w-5" />
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
                                class="text-gray-400 text-center py-4"
                            >
                                Data Pegawai Tidak Ditemukan
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
                                v-if="link.url && filterDataPegawai.length"
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
