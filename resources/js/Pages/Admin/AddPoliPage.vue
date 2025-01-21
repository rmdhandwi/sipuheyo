<script setup>
import Layout from "@/dashboard/Layout.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { onMounted } from "vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    dokters: {
        type: Array,
    },
    pegawais: {
        type: Array,
    },
    poli: {
        type: Array,
    },
    id: Number,
});

const id = props.id;
console.log("inidatta:", id);

const form = useForm({
    id: 0,
    kode: "",
    nama: "",
    penyakit: "",
    keterangan: "",
    dokter_id: "",
    pegawai_id: "",
});

function addNewItem() {
    console.log("siap");
    window.location = "/admin/poli/add";
}

function backAction(params) {
    window.location = "/admin/poli";
}

const save = () => {
    if (form.id <= 0) {
        form.post(route("admin.poli.post"), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500,
                });
                form.reset();
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
        });
    } else {
        form.put(route("admin.poli.put", form.id), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            },
        });
    }
};

onMounted(() => {
    if (!props.dokters.length || !props.pegawais.length) {
        Swal.fire({
            icon: "info",
            title: "Data Tidak Lengkap",
            text: "Pastikan data Dokter dan Petugas tersedia sebelum menambah Poli.",
            showConfirmButton: true,
        }).then(() => {
            backAction();
        });
    }


    if (id) {
        form.id = props.poli.id;
        form.kode = props.poli.kode;
        form.nama = props.poli.nama;
        form.penyakit = props.poli.penyakit;
        form.keterangan = props.poli.keterangan;
        form.jenis = props.poli.jenis;
        form.dokter_id = props.poli.dokter_id;
        form.pegawai_id = props.poli.pegawai_id;
    }
});
</script>

<template>
    <Layout>
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">DATA POLI</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <form @submit.prevent="save">
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Nama Poli</label>
                                <input
                                    type="text"
                                    v-model="form.nama"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['nama']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Penyakit</label>
                                <input
                                    type="text"
                                    v-model="form.penyakit"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError
                                    :message="form.errors['penyakit']"
                                />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Keterangan</label>
                                <textarea
                                    v-model="form.keterangan"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                ></textarea>
                                <InputError
                                    :message="form.errors['keterangan']"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Dokter</label>
                                <select
                                    type="text"
                                    v-model="form.dokter_id"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
                                        :value="item.id"
                                        v-if="props.pegawais.length"
                                        v-for="item in dokters"
                                    >
                                        {{ item.nama }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors['dokter_id']"
                                />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Pegawai</label>
                                <select
                                    type="text"
                                    v-model="form.pegawai_id"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
                                        :value="item.id"
                                        v-if="props.pegawais.length"
                                        v-for="item in pegawais"
                                    >
                                        {{ item.nama }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors['pegawai_id']"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="m-2 flex justify-end">
                        <button
                            type="button"
                            @click="backAction()"
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
                </form>
            </div>
        </div>
    </Layout>
</template>
