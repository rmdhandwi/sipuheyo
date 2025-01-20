<script setup>
import DokterLayout from "@/Layouts/DokterLayout.vue";
import PoliLayout from "@/Layouts/PoliLayout.vue";

const props = defineProps({
    polis: Object,
    dokter: Object,
    rekammediks: Array,
});

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
    <PoliLayout :poli="props.polis" :dokter="props.dokter">
        <div class="p-5">
            <div
                class="max-w-full overflow-x-auto rounded-lg shadow-lg bg-white"
            >
                <div class="p-6 space-y-6">
                    <div class="flex justify-between items-center mt-4">
                        <h2 class="text-xl font-medium text-gray-700">
                            Informasi Pasien
                        </h2>

                        <a
                            :href="'/poli/pasien'"
                            class="bg-red-500 text-white px-4 py-1 rounded-lg shadow hover:bg-red-700 transition-all focus:ring focus:ring-red-300"
                        >
                            Kembali
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >NIK</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.nik }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Nama</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.nama }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Jenis Kelamin</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.jk }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Tempat Lahir</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.tempat_lahir }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Tanggal Lahir</label
                            >
                            <p class="text-base text-gray-800">
                                {{
                                    getDate(rekammediks[0].pasien.tanggal_lahir)
                                }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Kontak</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.kontak }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Alamat</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.alamat }}
                            </p>
                        </div>
                        <div
                            class="flex flex-col bg-gray-50 p-4 rounded-lg shadow"
                        >
                            <label
                                class="text-sm font-semibold text-gray-500 mb-1"
                                >Email</label
                            >
                            <p class="text-base text-gray-800">
                                {{ rekammediks[0].pasien.email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PoliLayout>
</template>
