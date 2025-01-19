<script setup>
import Layout from "@/dashboard/Layout.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import RekamMedik from "@/Models/RekamMedik";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";

const props = defineProps({
    dokters: {
        type: Array,
    },
    polis: {
        type: Array,
    },
    pasiens: {
        type: Array,
    },
    obats: {
        type: Array,
    },

    rekammedik: RekamMedik,
});

const form = useForm({
    dokter_id: "",
    pasien_id: "",
    poli_id: "",
    konsultasi_berikut: null,
    kondisi: { berat: 0, tinggi: 0, lingkar_badan: 0, tekanan_darah: "" },
    keluhan: {},
    penanganan: {},
    resep: { obat: "", dosisi: "", catatan: "" },
});

function backAction() {
    window.location = "/admin/rekammedik";
}

function onChange(event) {
    if (props.polis && props.dokters) {
        const selectedPoliId = event.target.value;
        const selectedPoli = props.polis.find((x) => x.id == selectedPoliId);

        if (selectedPoli) {
            form.dokter_id = selectedPoli.dokter_id;
        }
    }
}

const save = () => {
    form.post(route("admin.rekammedik.post"), {
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

const searchQuery = ref("");
const filteredPasiens = ref(props.pasiens);

const searchPasien = () => {
    filteredPasiens.value = props.pasiens.filter((pasien) =>
        pasien.nama.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
};

const selectPasien = (pasien) => {
    form.pasien_id = pasien.id;
    searchQuery.value = pasien.nama;
    filteredPasiens.value = [];
};
</script>

<template>
    <Layout class="noprint">
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">PENDAFTARAN</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <form @submit.prevent="save">
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Pasien</label>
                                <input
                                    v-model="searchQuery"
                                    @input="searchPasien"
                                    type="text"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                    placeholder="Cari Pasien..."
                                />
                                <!-- Dropdown for showing suggestions -->
                                <div
                                    v-if="filteredPasiens.length > 0"
                                    class="mt-2 border rounded-lg max-h-48 overflow-y-auto"
                                >
                                    <ul>
                                        <li
                                            v-for="pasien in filteredPasiens"
                                            :key="pasien.id"
                                            @click="selectPasien(pasien)"
                                            class="p-2 cursor-pointer hover:bg-gray-200"
                                        >
                                            {{ pasien.nama }}
                                        </li>
                                    </ul>
                                </div>
                                <InputError
                                    :message="form.errors['pasien_id']"
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
                                        :value="item.id"
                                        v-for="item in polis"
                                    >
                                        {{ item.nama }}
                                    </option>
                                </select>
                                <InputError :message="form.errors['poli_id']" />
                            </div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Dokter</label>
                                <select
                                    disabled
                                    v-model="form.dokter_id"
                                    class="rounded-lg bg-transparent text-neutral-700"
                                >
                                    <option
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
    </Layout>
</template>
