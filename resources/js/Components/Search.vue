<script setup>
import Search from '@/Components/Search.vue';
import { reactive } from 'vue';


const search = reactive({ text: '' });
const emit = defineEmits(['onSearch']);


function onChangeText(event) {
    emit('onSearch', event.target.value);
}

</script>


<template>
    <div class="group relative hidden h-full w-full items-center md:flex lg:w-64">
        <svg style="color: red;" class="pointer-events-none absolute left-0 ml-3 hidden h-4 w-4  text-slate-800 sm:block"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
            </path>
        </svg><input type="text" v-model="search.text" v-on:change="onChangeText"
            class="block w-full rounded-2xl py-1.5 pl-10 pr-4 leading-normal text-gray-800 opacity-90 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Search">
    </div>
</template>