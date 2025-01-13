<script setup>
import Layout from "@/dashboard/Layout.vue";
import { useForm , Head} from "@inertiajs/vue3";
import Obat from "@/Models/Obat";
import Swal from "sweetalert2";
import { onMounted } from "vue";
import InputError from "@/Components/InputError.vue";
import Helper from "@/heper";


const props = defineProps({
    obat: {
        type: Obat,
    },
});

const form = useForm({
    id: 0,
    kode: "",
    nama: "",
    merek: "",
    dosis: "",
    deskripsi: "",
    kemasan: "",
    exp: new Date(),
});

function addNewItem() {
    console.log("siap");
    window.location = "/admin/obat/add";
}

function backAction(params) {
    window.location = "/admin/obat";
}

const save = () => {
    if (form.id <= 0) {
        form.post(route("admin.obat.post"), {
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
        form.put(route("admin.obat.put", form.id), {
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
    if (props.obat) {
        form.id = props.obat.id;
        form.kode = Helper.getKode(props.obat.id, "Obat");
        form.nama = props.obat.nama;
        form.merek = props.obat.merek;
        form.dosis = props.obat.dosis;
        form.deskripsi = props.obat.deskripsi;
        form.kemasan = props.obat.kemasan;
        form.exp = props.obat.exp;
    }
});
</script>

<template>
    <Head title="Tambah Obat"/>
    <Layout>
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">OBAT</h1>
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
                                <label class="mb-2">Nama Obat</label>
                                <input
                                    type="text"
                                    v-model="form.nama"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['nama']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Merek</label>
                                <input
                                    type="text"
                                    v-model="form.merek"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['merek']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Deskripsi</label>
                                <textarea
                                    v-model="form.deskripsi"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                ></textarea>
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Dosis</label>
                                <input
                                    type="text"
                                    v-model="form.dosis"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['dosis']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Kemasan</label>
                                <input
                                    type="text"
                                    v-model="form.kemasan"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                                <InputError :message="form.errors['kemasan']" />
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
