<script setup>
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { onMounted, reactive, ref } from "vue";
import Tab from "@/Components/Tab.vue";
import RekamMedik from "@/Models/RekamMedik";
import Pasien from "@/Models/Pasien";
import AddIcon from "@/Icons/AddIcon.vue";
import DeleteIcon from "@/Icons/DeleteIcon.vue";
import InputError from "@/Components/InputError.vue";
import PoliLayout from "@/Layouts/PoliLayout.vue";

const props = defineProps({
    polis: Array,
    pasien: Pasien,
    dokters: Array,
    obats: Array,
    rekammedik: RekamMedik,
    poli: Array,
    pegawai: Array,
    rm: Object,
});

const selectedTab = reactive({ id: 1 });

const form = useForm({
    antrian: "",
    nama: "",
    dokter_id: "",
    pasien_id: 0,
    poli_id: "",
    kondisi: { berat: 0, tinggi: 0, lingkar_badan: 0, tekanan_darah: "" },
    keluhan: [],
    status: "",
    file: null,
});

const fileX = ref(null);
const fileData = reactive({ image: false, preview: null });

function backAction() {
    window.location = "/poli/rekammedik";
}

const save = () => {
    // Validasi inputan
    if (
        !form.kondisi.berat ||
        !form.kondisi.tinggi ||
        !form.kondisi.lingkar_badan ||
        !form.kondisi.tekanan_darah
    ) {
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: "Semua kolom kondisi harus diisi.",
            showConfirmButton: true,
        });
        return; // Menghentikan pengiriman form jika ada yang kosong
    }

    if (
        !form.keluhan ||
        form.keluhan.length === 0 ||
        form.keluhan.some((keluhan) => !keluhan.value)
    ) {
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: "Semua kolom keluhan harus diisi.",
            showConfirmButton: true,
        });
        return; // Menghentikan pengiriman form jika ada keluhan yang kosong
    }

    form.post(route("poli.rekammedik.put", form.id), {
        onSuccess: () => {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Data Berhasil Disimpan",
                showConfirmButton: false,
                timer: 1500,
            });
        },
        onError: (errors) => {
            if (errors.error) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: errors.error,
                    timer: 5000,
                    showConfirmButton: false,
                });
            }
        },
    });
};

function addKeluhan() {
    form.keluhan.push({ value: "" });
}

function deleteKeluhan(item) {
    const index = form.keluhan.indexOf(item);
    if (index > -1) form.keluhan.splice(index, 1);
}

onMounted(() => {
    if (props.rekammedik) {
        const {
            id,
            pasien_id,
            dokter_id,
            poli_id,
            tanggal,
            status,
            hasil_lab,
            keluhan,
            antrian,
        } = props.rekammedik;
        form.id = id;
        form.antrian = antrian;
        form.pasien_id = pasien_id;
        form.dokter_id = dokter_id;
        form.poli_id = poli_id;
        form.tanggal = tanggal;
        form.status = status;
        form.kondisi = props.rekammedik?.kondisi
            ? JSON.parse(props.rekammedik.kondisi)
            : { berat: 0, tinggi: 0, lingkar_badan: 0, tekanan_darah: "" };
        form.keluhan = JSON.parse(keluhan);
        fileData.preview = hasil_lab || null;

        // Jika keluhan kosong, tambahkan satu elemen default
        if (form.keluhan.length === 0) {
            form.keluhan.push({ value: "" });
        }
    }
});

const tabs = [{ id: 1, name: "Kondisi dan Keluhan" }];
const selectTab = (param) => {
    selectedTab.id = param.id;
};

const readFile = () => {
    const file = fileX.value?.files?.[0]; // Mengambil file yang dipilih
    if (file) {
        fileData.image = true;
        fileData.preview = URL.createObjectURL(file); // Tampilkan preview file
        form.file = file; // Simpan file langsung ke form
    }
};


function getDate(dateString) {
    const date = new Date(dateString);

    // Mengambil Tahun, Bulan, dan Tanggal
    const year = date.getFullYear();
    const month = ("0" + (date.getMonth() + 1)).slice(-2);
    const day = ("0" + date.getDate()).slice(-2);

    return `${day}/${month}/${year}`;
}
</script>

<template>
    <Head title="Detail Pasien" />
    <PoliLayout class="noprint" :poli="props.poli" :pegawai="props.pegawai">
        <div class="p-5 mt-5 flex flex-col">
            <h1 class="text-xl text-center mb-5">DETAIL BEROBAT</h1>
            <div class="flex justify-between items-center">
                <h1 class="text-xl text-gray-500">Nomor Rekam Medik : {{ props.rm.pasien.rekammedik }}</h1>
                <h1 class="text-xl text-gray-500">Tanggal Berobat : {{ getDate(props.rm.tanggal) }}</h1>
            </div>
        </div>

        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <div class="grid grid-cols-2">
                    <div>
                        <div class="flex flex-col p-3">
                            <label class="mb-2 font-bold">Antrian</label>
                            <span
                                class="rounded-lg bg-gray-100 px-3 py-2 text-neutral-700"
                            >
                                {{ form.antrian || "-" }}
                            </span>
                        </div>
                        <div class="flex flex-col p-3">
                            <label class="mb-2 font-bold">Pasien</label>
                            <span
                                class="rounded-lg bg-gray-100 px-3 py-2 text-neutral-700"
                            >
                                {{ pasien.nama || "-" }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-col p-3">
                            <label class="mb-2 font-bold">Nama Poli</label>
                            <span
                                class="rounded-lg bg-gray-100 px-3 py-2 text-neutral-700"
                            >
                                {{
                                    polis.find(
                                        (item) => item.id === form.poli_id
                                    )?.nama || "-"
                                }}
                            </span>
                        </div>
                        <div class="flex flex-col p-3">
                            <label class="mb-2 font-bold">Dokter</label>
                            <span
                                class="rounded-lg bg-gray-100 px-3 py-2 text-neutral-700"
                            >
                                {{
                                    dokters.find(
                                        (item) => item.id === form.dokter_id
                                    )?.nama || "-"
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="save">
            <div class="p-5">
                <Tab
                    :items="tabs"
                    :tabActive="selectedTab.id"
                    @onClickTab="selectTab"
                />
                <div v-if="selectedTab.id == 1">
                    <div class="p-5 mt-5 flex justify-between shadow-md">
                        <h1 class="text-2xl">KONDISI</h1>
                        <div class="m-2 flex justify-end">
                            <button
                                type="button"
                                @click="backAction"
                                class="mx-1 rounded-full border border-rose-300 px-5 py-1 text-white bg-rose-500"
                            >
                                Kembali
                            </button>
                            <button
                                type="submit"
                                class="mx-1 rounded-full border border-sky-500 px-5 py-1 text-white bg-sky-700"
                            >
                                Simpan
                            </button>
                        </div>
                    </div>

                    <ul class="p-5">
                        <li class="mb-3">
                            <div class="flex items-center">
                                <h6>Berat Badan</h6>
                                <input
                                    type="number"
                                    v-model="form.kondisi.berat"
                                    class="w-20 mx-3 mr-10 rounded-lg bg-transparent text-neutral-700"
                                />
                                <h6>Tinggi Badan</h6>
                                <input
                                    type="number"
                                    v-model="form.kondisi.tinggi"
                                    class="mx-3 mr-10 rounded-lg bg-transparent text-neutral-700"
                                />
                                <h6>Lingkar Badan</h6>
                                <input
                                    type="number"
                                    v-model="form.kondisi.lingkar_badan"
                                    class="mx-3 mr-10 rounded-lg bg-transparent text-neutral-700"
                                />
                                <h6>Tekanan Darah</h6>
                                <input
                                    type="text"
                                    v-model="form.kondisi.tekanan_darah"
                                    class="mx-3 rounded-lg bg-transparent text-neutral-700"
                                />
                            </div>
                        </li>
                    </ul>

                    <div class="p-5 mt-5 flex justify-between shadow-md">
                        <h1 class="text-2xl">KELUHAN</h1>
                        <AddIcon
                            class="w-7 text-teal-500 cursor-pointer"
                            @click="addKeluhan"
                        />
                    </div>

                    <ul class="p-5 mt-5 shadow-md">
                        <InputError :message="form.errors['keluhan']" />
                        <li
                            v-for="(item, key) in form.keluhan"
                            :key="key"
                            class="flex gap-1"
                        >
                            <input
                                type="text"
                                :value="key + 1"
                                disabled
                                class="w-12 rounded-lg bg-transparent text-neutral-700"
                            />
                            <input
                                type="text"
                                v-model="item.value"
                                class="w-full rounded-lg bg-transparent text-neutral-700"
                            />
                            <DeleteIcon
                                @click="deleteKeluhan(item)"
                                class="w-7 text-red-500"
                            />
                        </li>
                    </ul>

                    <div class="p-5 mt-5 flex justify-between shadow-md">
                        <h1 class="text-2xl">Hasil Lab</h1>
                    </div>

                    <ul class="p-5">
                        <li class="mb-3">
                            <input type="file" ref="fileX" @change="readFile" />
                            <img
                                v-if="fileData.preview"
                                class="mt-3 w-full h-auto"
                                :src="fileData.preview"
                            />
                            <p v-else>No image uploaded</p>
                            <InputError
                                class="mt-4"
                                :message="form.errors['hasil_lab']"
                            />
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </PoliLayout>
</template>
