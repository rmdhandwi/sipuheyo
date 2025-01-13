<script setup>
import Layout from "@/dashboard/Layout.vue";
import { useForm, Head } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { onMounted } from "vue";
import Pegawai from "@/Models/Pegawai";
import InputError from "@/Components/InputError.vue";
import Helper from "@/heper";

const props = defineProps({
    pegawai: {
        type: Pegawai,
    },
});

const form = useForm({
    id: 0,
    nama: "",
    jk: "",
    email: "",
    bagian: "",
    kontak: "",
});

function addNewItem() {
    console.log("siap");
    window.location = "/admin/pegawai/add";
}

function backAction(params) {
    window.location = "/admin/pegawai";
}

const save = () => {
    if (form.id <= 0) {
        form.post(route("admin.pegawai.post"), {
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
        form.put(route("admin.pegawai.put", form.id), {
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
    if (props.pegawai) {
        form.id = props.pegawai.id;
        form.kode = Helper.getKode(props.pegawai.id, "Pegawai");
        form.nama = props.pegawai.nama;
        form.jk = props.pegawai.jk;
        form.email = props.pegawai.email;
        form.bagian = props.pegawai.bagian;
        form.kontak = props.pegawai.kontak;
    }
});
</script>

<template>
    <Head title="Tambah Pegawai"/>
    <Layout>
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">Pegawai</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <form @submit.prevent="save">
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="hidden">
                                <label class="mb-2">Kode</label>
                                <input
                                    type="text"
                                    v-model="form.kode"
                                    disabled
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Nama Pegawai</label>
                                <input
                                    type="text"
                                    v-model="form.nama"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['nama']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Jenis Kelamin</label>
                                <select
                                    type="text"
                                    v-model="form.jk"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <InputError :message="form.errors['jk']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Kotak</label>
                                <input
                                    type="number"
                                    v-model="form.kontak"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['kontak']" />
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Email</label>
                                <input
                                    type="email"
                                    v-model="form.email"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['email']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Bagian</label>
                                <input
                                    type="text"
                                    v-model="form.bagian"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['bagian']" />
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

<style scoped>
/* Menghilangkan spinner pada input number */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield; /* Untuk Firefox */
}
</style>
