<script setup>
import LogoKota from "@/Icons/LogoKota.vue";
import LogoPuskesmas from "@/Icons/LogoPuskesmas.vue";
import Layout from "@/dashboard/Layout.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import { ref, computed, reactive, onMounted } from "vue";
import Search from "@/Components/Search.vue";
import AddIcon from "@/Icons/AddIcon.vue";
import Helper from "@/heper";
import DatePicker from "@/Components/DatePicker.vue";
import PrinterIcon from "@/Icons/PrinterIcon.vue";
import { useRoute, useRouter } from "vue-router";

const props = defineProps({
    data: {
        type: Array,
    },
    polis: {
        type: Array,
    },
});

const router = useRouter();
var date = new Date();
let tgl =
    date.getFullYear() +
    "-" +
    Helper.getPadNumber(date.getMonth() + 1) +
    "-" +
    Helper.getPadNumber(date.getDate());
const data = reactive({ rekamMedik: Array, poli: null, searchText: "" });

const onChangeDate = (date) => {
    if (data.poli == null) {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "pilih poli",
            showConfirmButton: false,
            timer: 1500,
        });
    } else if (data.poli && data.poli.id > 0) {
        axios
            .get(Helper.apiUrl + "/rekammedik/" + data.poli.id + "/" + date)
            .then((response) => {
                data.rekamMedik = response.data;
            });
    }
};

let form = useForm({
    id: 0,
    dokter_id: 0,
    pasien_id: 0,
    poli_id: 0,
    tanggal: null,
    konsultasi_berikut: null,
    keluhan: null,
    penanganan: null,
    resep: null,
    status: "",
});

function addNewItem() {
    console.log("siap");
    window.location = "/admin/rekammedik/add";
}

function backAction(params) {
    window.location = "/admin/poli";
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

function approveItem(item) {
    Swal.fire({
        title: "Anda Yakin ?",
        text: "Menyetujui Pendaftaran ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, approve it!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.id = item.id;
            form.dokter_id = item.dokter_id;
            form.pasien_id = item.pasien_id;
            form.poli_id = item.poli_id;
            form.tanggal = item.tanggal;
            form.konsultasi_berikut = item.konsultasi_berikut;
            form.keluhan = item.keluhan;
            form.penanganan = item.penanganan;
            form.resep = item.resep;
            form.status = "admin";

            form.put(route("admin.rekammedik.put", item.id), {
                onSuccess: (res) => {
                    Swal.fire({
                        title: "Approve!",
                        text: "Data Berhasil Di disetujui.",
                        icon: "success",
                    });

                    item.status = "admin";
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
    if (!props.polis.length) {
        Swal.fire({
            icon: "info",
            title: "Data Tidak Lengkap",
            text: "Pastikan data Poli tersedia.",
            showConfirmButton: true,
        }).then(() => {
            backAction();
        });
    }
});

const printReport = () => {
    if (data.rekamMedik && data.rekamMedik.length > 0) {
        window.print();
    }
};

const onSearchText = (text) => {
    searchTerm.value = text;
};

const searchTerm = ref("");
const filterDataRekamMedik = computed(() => {
    if (searchTerm.value === "") {
        return props.data;
    }

    let matches = 0;
    return props.data.filter((item) => {
        if (
            (item.dokter.nama
                .toLowerCase()
                .includes(searchTerm.value.toLowerCase()) ||
                item.poli.nama
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) ||
                item.pasien.nama
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) ||
                item.kode
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase()) ||
                item.tanggal
                    .toLowerCase()
                    .includes(searchTerm.value.toLowerCase())) &&
            matches < 10
        ) {
            matches++;
            return item;
        }
    });
});

const onChangeSearch = (text) => {
    if (!text) {
        data.rekamMedik = [];
        return;
    }

    axios.get(Helper.apiUrl + "/rekammedik/all").then((response) => {
        const datax = response.data;
        if (datax) {
            const sText = text.toLocaleLowerCase();
            data.rekamMedik = datax.filter(
                (x) =>
                    x.antrian.toLowerCase().includes(sText) ||
                    x.pasien.nama.toLowerCase().includes(sText) ||
                    x.dokter.nama.toLowerCase().includes(sText) ||
                    x.poli.nama.toLowerCase().includes(sText)
            );
        }
    });
};
</script>

<template>
    <Layout class="noprint">
        <div class="mt-5 flex justify-between">
            <h1 class="text-xl">DATA REKAM MEDIK</h1>
            <div class="flex">
                <AddIcon
                    class="cursor-pointer text-teal-500 w-12"
                    @click="addNewItem()"
                ></AddIcon>
                <PrinterIcon
                    class="cursor-pointer text-amber-600 w-12"
                    @click="printReport()"
                ></PrinterIcon>
            </div>
        </div>
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <label class="mx-2">Poli</label>
                <select
                    type="text"
                    v-model="data.poli"
                    required
                    class="mx-2 rounded-lg bg-transparent text-neutral-700"
                >
                    <option
                        v-if="props.polis.length"
                        class="p-2"
                        :value="item"
                        v-for="item in polis"
                    >
                        {{ item.nama }}
                    </option>
                </select>
                <label class="mx-2 ml-10">Tanggal</label>
                <DatePicker v-on:on-change-date="onChangeDate"></DatePicker>
            </div>
            <div class="flex items-center">
                <Search v-on:on-search="onChangeSearch"></Search>
            </div>
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
                                Kode
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
                            <td class="border-b border-gray-200 p-3 text-sm">
                                <p class="whitespace-nowrap capitalize">
                                    {{ item.status }}
                                </p>
                            </td>

                            <td
                                class="border-b border-gray-200 p-3 text-sm flex"
                            >
                                <a
                                    :href="'/admin/rekammedik/add/' + item.id"
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
                    </tbody>
                </table>
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

            <div>
                <div class="flex">
                    <div class="w-32">Nama Poli</div>
                    <div>: {{ data.poli?.nama }}</div>
                </div>
                <div class="flex">
                    <div class="w-32">Penyakit</div>
                    <div>: {{ data.poli?.penyakit }}</div>
                </div>
                <div class="flex">
                    <div class="w-32">Tanggal</div>
                    <div>:</div>
                </div>
            </div>

            <table class="w-full mt-3">
                <thead>
                    <tr>
                        <th>Kode Antrian</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in data.rekamMedik">
                        <td>
                            <p class="whitespace-nowrap">{{ item.antrian }}</p>
                        </td>
                        <td>
                            <p class="whitespace-nowrap">{{ item.tanggal }}</p>
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
