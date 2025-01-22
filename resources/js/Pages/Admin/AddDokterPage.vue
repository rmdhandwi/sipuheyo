<script setup>
import Layout from "@/dashboard/Layout.vue";
import { useForm, Head } from "@inertiajs/vue3";
import Dokter from "@/Models/Dokter";
import Swal from "sweetalert2";
import { onMounted } from "vue";
import InputError from "@/Components/InputError.vue";
import Helper from "@/heper";

const props = defineProps({
    dokter: {
        type: Dokter,
    },
});

const form = useForm({
    id: 0,
    nid: "",
    nama: "",
    jk: "",
    email: "",
    spesialis: "",
    kontak: "",
});

function addNewItem() {
    console.log("siap");
    window.location = "/admin/dokter/add";
}

function backAction(params) {
    window.location = "/admin/dokter";
}

const save = () => {
    if (form.id <= 0) {
        form.post(route("admin.dokter.post"), {
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
        form.put(route("admin.dokter.put", form.id), {
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
    if (props.dokter) {
        form.id = props.dokter.id;
        form.kode = Helper.getKode(props.dokter.id, "Dokter");
        form.nid = props.dokter.nid;
        form.nama = props.dokter.nama;
        form.jk = props.dokter.jk;
        form.email = props.dokter.email;
        form.spesialis = props.dokter.spesialis;
        form.kontak = props.dokter.kontak;
    }
});
</script>

<template>
    <Head title="Tambah Dokter" />
    <Layout>
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">Tambah/Edit Dokter</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <form @submit.prevent="save">
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="hidden flex-col p-3">
                                <label class="mb-2">Kode</label>
                                <input
                                    type="text"
                                    v-model="form.kode"
                                    disabled
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">NID</label>
                                <input
                                    type="text"
                                    maxlength="18"
                                    inputmode="number"
                                    v-model="form.nid"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['nid']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Nama Dokter</label>
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
                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Spesialis</label>
                                <input
                                    type="text"
                                    v-model="form.spesialis"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError
                                    :message="form.errors['spesialis']"
                                />
                            </div>
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
                                <label class="mb-2">Kontak</label>
                                <input
                                    type="text"
                                    v-model="form.kontak"
                                    maxlength="13"
                                    inputmode="number"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['kontak']" />
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
