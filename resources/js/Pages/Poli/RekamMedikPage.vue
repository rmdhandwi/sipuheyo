<script setup>
import EditIcon from '@/Icons/EditIcon.vue';
import DeleteIcon from '@/Icons/DeleteIcon.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue';
import Search from '@/Components/Search.vue';
import AddIcon from '@/Icons/AddIcon.vue';
import Helper from '@/heper';
import RekamMedik from '@/Models/RekamMedik';
import PasienLayout from '@/Layouts/PasienLayout.vue';
const props = defineProps({
    data: {
        type: Array
    }
})


console.log(props);

const form = useForm({
    id: 0
})

function addNewItem() {
    console.log("siap");
    window.location = "/admin/rekammedik/add";

}


function deleteItem(item) {
    Swal.fire({
        title: "Anda Yakin ?",
        text: "Menghapus Data !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('admin.rekammedik.delete', item.id), {
                onSuccess: (res) => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data Berhasil Di hapus.",
                        icon: "success"
                    });

                }, onError: (err) => {
                    Swal.fire({
                        title: "Error",
                        text: err,
                        icon: "error"
                    });
                }
            })


        }
    });
}




const onSearchText = (text) => {
    searchTerm.value = text;
};

const searchTerm = ref('');
const filterDataRekamMedik = computed(() => {
    if (searchTerm.value === '') {
        return props.data;
    }

    let matches = 0
    return props.data.filter(item => {
        if (
            (item.dokter.nama.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            item.poli.nama.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            item.pasien.nama.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            item.kode.toLowerCase().includes(searchTerm.value.toLowerCase())||
            item.tanggal.toLowerCase().includes(searchTerm.value.toLowerCase())
         )
            && matches < 10
        ) {
            matches++
            return item
        }
    })
});

</script>

<template>

    <PasienLayout>
        
        <div class=" mt-5 flex justify-between">
            <h1 class="text-xl">DATA REKAM MEDIK</h1>
            <div class="flex">
                <AddIcon class=" cursor-pointer text-teal-500  w-12" @click="addNewItem()"></AddIcon>
                
            </div>
        </div>
        <div>
            <Search v-on:on-search="onSearchText"></Search> 
        </div>
        <div class="py-5">
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
                                Pasien
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
                                class=" w-20 border-b border-gray-200  px-5 py-3 text-left text-sm font-normal uppercase text-neutral-500">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filterDataRekamMedik">
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.antrian }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.tanggal }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.pasien.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.poli.nama }}</p>
                            </td>
                            <td class="border-b border-gray-200  p-3 text-sm">
                                <p class="whitespace-nowrap">{{ item.dokter.nama }}</p>
                            </td>

                            <td class="border-b border-gray-200  p-3 text-sm flex">
                                <a :href="'/admin/rekammedik/add/' + item.id"
                                    class=" text-amber-500 hover:text-amber-700">
                                    <EditIcon class=" w-5" />
                                </a>
                                <a @click="deleteItem(item)" class=" cursor-pointer text-rose-600 hover:text-rose-900">
                                    <DeleteIcon class=" w-5" />
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </PasienLayout>

</template>