<script setup>
import Swal from "sweetalert2";
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    login: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
        onError: (errors) => {
            // SweetAlert untuk pesan error
            Swal.fire({
                icon: "error",
                title: "Login Gagal",
                text: errors.message || "Terjadi kesalahan, coba lagi.",
            });
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <InputLabel
                class="my-3 text-[22px] mb-4 font-semibold"
                value="Login"
            />

            <div>
                <InputLabel
                    for="login"
                    value="Email/No.Telepon"
                    class="text-gray-700"
                />
                <TextInput
                    id="login"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.login"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError
                    class="mt-2 text-red-400"
                    :message="form.errors.login"
                />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password"
                    value="Password"
                    class="text-gray-700"
                />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError
                    class="mt-2 text-red-400"
                    :message="form.errors.password"
                />
            </div>

            <div class="m-2 flex justify-between">
                <label class="flex items-center text-white">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-500">Remember me</span>
                </label>
                <!-- <div class="my-3">
                    <span class="text-sm">Belum Punya Akun?</span>
                    <Link
                        :href="route('pasien.register')"
                        class="!my-5 underline text-gray-500 text-sm  hover:text-gray-700"
                    >
                        Daftar
                    </Link>
                </div> -->
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    class="ms-4 bg-blue-600 hover:bg-blue-700 hover:-translate-y-2 text-white"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
