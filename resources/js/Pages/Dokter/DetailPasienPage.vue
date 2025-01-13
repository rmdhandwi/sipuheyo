<script setup>
import DokterLayout from '@/Layouts/DokterLayout.vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { onMounted } from 'vue';
import Pasien from '@/Models/Pasien';
import DetailListIcon from '@/Icons/DetailListIcon.vue';
import EditIcon from '@/Icons/EditIcon.vue';
import Poli from '@/Models/Poli';
import Helper from '@/heper';


const props = defineProps({
    poli: {
        type: Poli
    },
    pasien: {
        type: Pasien
    },
    rekammediks: {
        type: Array
    }
})


const form = useForm({
    "id": 0,
    "kode": '',
    "nama": '',
    "jk": 'pria',
    "tempat_lahir": '',
    "tanggal_lahir": null,
    "kontak": '',
    "alamat": '',
}
)


function addNewItem() {
    console.log("siap");
    window.location = "/admin/pasien/add";
}


function backAction(params) {
    window.location = "/admin/pasien";
}

const save = () => {

    if (pasien.id <= 0) {
        pasien.post(route('admin.pasien.post'), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
                pasien.reset();
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    } else {
        pasien.put(route('admin.pasien.put', pasien.id), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            onError: (err) => {
                if (err.message) {
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: err.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
        });
    }
}


onMounted(() => {


})

</script>

<template>

    <DokterLayout :poli="props.poli">
        <div class="p-5 mt-5 flex justify-between">
            <h1 class="text-xl">Detail Pasien</h1>
        </div>
        <div class="p-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow ">
                <form @submit.prevent="save">
                    <div class=" grid grid-cols-2">
                        <div>
                            <div class="flex flex-col p-3">
                                <label class="mb-2">Pasien</label>
                                <input type="text" :value="Helper.getKode(pasien.id,'pasien') + ' - ' + pasien.nama" disabled
                                    class=" rounded-lg bg-transparent  text-neutral-700">
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="p-5 flex justify-between">
            <h1 class="text-2xl">DATA REKAM MEDIK</h1>
            <button @click="addNewItem()"
                class="shrink-0 rounded-lg bg-gray-600 px-4 py-2 text-base font-semibold text-white shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200"
                type="submit">
                Tambah
            </button>
        </div>
        <div class="px-5">
            <div class="max-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full leading-normal">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Kode
                            </th>
                            <th scope="col"
                                class="border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Tanggal
                            </th>

                            <th scope="col"
                                class="border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Poli
                            </th>
                            <th scope="col"
                                class=" w-auto border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Dokter
                            </th>
                            <th scope="col"
                                class=" w-auto border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Konsultasi Selanjutnya
                            </th>
                            <th scope="col"
                                class=" w-20 border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in rekammediks">
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ Helper.getKode(item.id,'rekammedik') }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.tanggal }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.poli.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.dokter.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.konsultasi_berikut }}</p>
                            </td>

                            <td class="border-b border-gray-200  p-3 text-sm flex">
                                <a :href="'/dokter/rekammedik/' + item.id"
                                    class=" cursor-pointer text-cyan-600 hover:text-cyan-900">
                                    <DetailListIcon class=" w-5" />
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </DokterLayout>

</template>