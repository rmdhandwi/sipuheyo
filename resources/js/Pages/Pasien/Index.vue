<script setup>
import PasienLayout from "@/Layouts/PasienLayout.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import { onMounted, reactive } from "vue";
import Search from "@/Components/Search.vue";
import Check from "@/Icons/Check.vue";
import Info from "@/Icons/Info.vue";
import Panding from "@/Icons/Panding.vue";
import Wrong from "@/Icons/Wrong.vue";
import DetailListIcon from "@/Icons/DetailListIcon.vue";

const props = defineProps({
    rekammedik: {
        type: Array,
    },
    pasien: {
        type: Array,
    },
});

function formattedDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString("id-ID", {
        weekday: "long", // Nama hari (Senin, Selasa, dst)
        day: "2-digit", // Tanggal dengan dua digit
        month: "long", // Nama bulan (Januari, Februari, dst)
        year: "numeric", // Tahun
        hour: "2-digit", // Jam dengan dua digit
        minute: "2-digit", // Menit dengan dua digit
        hour12: false, // Menggunakan format 24 jam
    });
}

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
    <PasienLayout :pasien="props.pasien">
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
                        <tr v-for="item in data.rekamMedik">
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
                                    {{ item.dokter.nama }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap capitalize">
                                    {{
                                        formattedDateTime(
                                            item.konsultasi_berikut
                                        )
                                    }}
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
                                class="border-l border-gray-200 p-3 text-sm flex"
                            >
                                <a
                                    v-if="item.status !== 'Baru'"
                                    :href="
                                        '/pasien/rekammedik/detail/' + item.id
                                    "
                                    class="text-blue-500 hover:text-blue-700"
                                >
                                    <DetailListIcon class="w-5" />
                                </a>
                                <a
                                    v-if="item.status === 'Baru'"
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
