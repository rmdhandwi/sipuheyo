<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({errors: {}})


const form = useForm({
    nama: '',
    nik: '',
    email: '',
    jk: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    kontak: '',
    alamat: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {  

    form.post(route('pasien.register'), {
            onSuccess: (res) => {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Data Berhasil Disimpan ",
                    showConfirmButton: false,
                    timer: 1500
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
                        timer: 1500
                    });
                }
            }
        });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register Pasien" />
        <form @submit.prevent="submit">
            <InputLabel class=" m-3  text-xl"  value="Registrasi Pasien" />
            <div class=" grid grid-cols-2">
                <div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="NIK" value="NIK" />
                        <input type="text" v-model="form.nik" class=" rounded-lg bg-transparent  text-neutral-700 ">
                        <InputError :message="form.errors['nik']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="Nama Pasien" value="Nama Pasien" />
                        <input type="text" v-model="form.nama" class=" rounded-lg bg-transparent  text-neutral-700 ">
                        <InputError :message="form.errors['nama']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="Jenis Kelamin" value="Jenis Kelamin" />
                        <select type="text" v-model="form.jk" 
                            class="rounded-lg bg-transparent  text-neutral-700">
                            <option value="pria">Laki-Laki</option>
                            <option value="wanita">Perempuan</option>
                        </select>
                        <InputError :message="form.errors['jk']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="name" value="Email" />
                        <input type="email"  v-model="form.email"
                            class=" rounded-lg bg-transparent  text-neutral-700">
                        <InputError :message="form.errors['email']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="name" value="Password" />
                        <input type="password"  v-model="form.password"
                            class=" rounded-lg bg-transparent  text-neutral-700">
                        <InputError :message="form.errors['password']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="name" value="Confirm Passwrord" />
                        <input type="password"  v-model="form.password_confirmation"
                            class=" rounded-lg bg-transparent  text-neutral-700">
                        <InputError :message="form.errors['password_confirmation']" />
                    </div>
                  
                </div>
                <div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="name" value="Tempat Lahir" />
                        <input type="text" v-model="form.tempat_lahir"
                            class=" rounded-lg bg-transparent  text-neutral-700">
                        <InputError :message="form.errors['tempat_lahir']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="Tanggal Lahir" value="Tanggal Lahir" />
                        <input type="date" v-model="form.tanggal_lahir"
                            class=" rounded-lg bg-transparent  text-neutral-700">
                        <InputError :message="form.errors['tanggal_lahir']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="Kotak" value="Kotak" />
                        <input type="text" v-model="form.kontak" class=" rounded-lg bg-transparent  text-neutral-700">
                        <InputError :message="form.errors['kontak']" />
                    </div>
                    <div class="flex flex-col p-3">
                        <InputLabel for="Alamat" value="Alamat" />
                        <textarea v-model="form.alamat" class=" h-32 rounded-lg bg-transparent  text-neutral-700"></textarea>
                        <InputError :message="form.errors['alamat']" />
                    </div>

                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
