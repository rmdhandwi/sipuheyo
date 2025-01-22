<script setup>
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import PrintResep from "@/Components/PrintResep.vue";
import PasienLayout from "@/Layouts/PasienLayout.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    polis: {
        type: Array,
    },
    pasien: {
        type: Array,
    },
    dokters: {
        type: Array,
    },
});

const form = useForm({
    nama: "",
    dokter_id: "",
    pasien_id: props.pasien.id,
    poli_id: "",
    konsultasi_berikut: null,
    kondisi: { berat: 0, tinggi: 0, lingkar_badan: 0, tekanan_darah: "" },
    keluhan: [],
    penanganan: [],
    resep: [],
});

function backAction(params) {
    window.location = "/pasien";
}

function onChange(event) {
    if (props.rekammediks) {
        var poli = props.rekammediks.find((x) => x.id == event.target.value);
        if (poli) {
            form.dokter_id = poli.dokter_id;
        }
    } else {
        var poli = props.polis.find((x) => x.id == event.target.value);
        form.dokter_id = poli.dokter_id;
    }
}

const save = () => {
    form.post(route("pasien.rekammedik.post"), {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Pendaftaran berhasil!",
                timer: 5000,
                showConfirmButton: false,
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
</script>

<template>
    <PasienLayout class="noprint" :pasien="props.pasien">
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">PENDAFTAR BEROBAT</h1>
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
                                <label class="mb-2">Pasien</label>
                                <input
                                    type="text"
                                    v-model="pasien.nama"
                                    disabled
                                    class="rounded-lg bg-transparent text-neutral-700"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Nama Poli</label>
                                <select
                                    type="text"
                                    v-model="form.poli_id"
                                    @change="onChange($event)"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
                                        v-if="props.polis.length"
                                        :value="item.id"
                                        v-for="item in polis"
                                    >
                                        {{ item.nama + " - " + item.penyakit }}
                                    </option>
                                </select>
                                <InputError :message="form.errors['poli_id']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Dokter</label>
                                <select
                                    disabled
                                    type="text"
                                    v-model="form.dokter_id"
                                    required
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
                                        v-if="props.dokters.length"
                                        :value="item.id"
                                        v-for="item in dokters"
                                    >
                                        {{ item.nama }}
                                    </option>
                                </select>
                                <InputError
                                    :message="form.errors['dokter_id']"
                                />
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
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </PasienLayout>

    <PrintResep
        v-if="rekammedik && rekammedik.resep"
        :obats="props.obats"
        :rekammedik="props.rekammedik"
    ></PrintResep>
</template>
