<script setup>
import PasienLayout from "@/Layouts/PasienLayout.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import { onMounted, reactive } from "vue";
import Search from "@/Components/Search.vue";

const props = defineProps({
    rekammedik: {
        type: Array,
    },
});

const data = reactive({ rekamMedik: Array });

const form = useForm({
    id: 0,
});

function getDate(dateString) {
    const date = new Date(dateString);

    // Mengambil Tahun, Bulan, dan Tanggal
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);

    return `${day}-${month}-${year}`;
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
            form.delete(route("pasien.rekammedik.delete", item.id), {
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

onMounted(() => {
    data.rekamMedik = props.rekammedik.sort(function (a, b) {
        return new Date(b.tanggal) - new Date(a.tanggal);
    });
});

const onChangeSearch = (text) => {
    const sText = text.toLocaleLowerCase();
    const xx = props.rekammedik.filter(
        (x) =>
            x.antrian.toLowerCase().includes(sText) ||
            x.dokter.nama.toLowerCase().includes(sText) ||
            x.poli.nama.toLowerCase().includes(sText)
    );
    data.rekamMedik = xx.sort(function (a, b) {
        return new Date(b.tanggal) - new Date(a.tanggal);
    });
};
</script>

<template>
    <PasienLayout :poli="props.poli">
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">DATA REKAM MEDIKX</h1>
            <Search v-on:on-search="onChangeSearch"></Search>
        </div>
        <div></div>
        <div class="py-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                scope="col"
                                class="border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Kode Antrian
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
                                Poli
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Dokter
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Status
                            </th>
                            <th
                                scope="col"
                                class="w-auto border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Kunjungan Berikut
                            </th>
                            <th
                                scope="col"
                                class="w-20 border-b border-gray-200 px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500"
                            >
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data.rekamMedik">
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.antrian }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.tanggal }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap">
                                    {{ item.poli.nama }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap capitalize">
                                    {{ item.dokter.nama }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap capitalize">
                                    {{ item.status }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap capitalize">
                                    {{ item.konsultasi_berikut }}
                                </p>
                            </td>

                            <td
                                class="border-b border-gray-200 p-3 text-sm flex"
                            >
                                <a
                                    @click="deleteItem(item)"
                                    class="cursor-pointer text-rose-600 hover:text-rose-900"
                                >
                                    <DeleteIcon class="w-5" />
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </PasienLayout>
</template>
