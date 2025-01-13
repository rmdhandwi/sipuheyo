<script setup>
import Search from '@/Components/Search.vue';
import { reactive } from 'vue';


const search = reactive({ date:new Date() });
const emit = defineEmits(['onChangeDate']);
let model = defineModel();
function changeDate(event) {
    emit('onChangeDate', event.target.value);
    model = event.target.value;
}
</script>

<template>
    <div class="group relative hidden h-full w-full items-center md:flex lg:w-64">
        <input type="date" v-model="model" v-on:change="changeDate"
            class="block rounded-lg py-1.5 pl-2 pr-4  bg-transparent  leading-normal opacity-90 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Tanggal">
    </div>
</template>