<script setup>
import { ref, computed, onMounted } from 'vue'



const props = defineProps({
    pasiens: {
        type: Array
    },
    value: 0
})




const emit = defineEmits(['onSelectPasien'])


let searchTerm = ref('')
let selected = ref(null);

const selectedItem = (item) => {
    searchTerm.value = item.nama;
    selected.value =item;
    emit('onSelectPasien', item);
}

const searchCountries = computed(() => {
    if (searchTerm.value === '') {
        return []
    }

    let matches = 0
    selected.value = null;
    return props.pasiens.filter(item => {
        if (
            item.nama.toLowerCase().includes(searchTerm.value.toLowerCase())
            && matches < 10
        ) {
            matches++
            return item
        }
    })
});

onMounted(() => {
    console.log(props.pasiens)
    setTimeout(() => {
        var data = props.pasiens.find(x => x.id == props.value);
        if (data) {
            selectedItem(data);
        }
    }, 500);
})
</script>

<template>
    <input type="text" placeholder="Type here..." v-model="searchTerm" id="search" class="bg-transparent">
    <ul v-if="selected == null">
        <li v-on:click="selectedItem(item)" v-for="(item) in searchCountries" 
            class=" p-2 bg-zinc-400  opacity-95">
            {{ item.nama }}
        </li>
    </ul>
</template>