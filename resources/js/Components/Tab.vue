<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    tabActive: 0,
    items: {},
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});


onMounted(()=>{
    tabActive.value = props.tabActive;

})

const emitTab = defineEmits(['onClickTab'])

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};


const open = ref(false);
const tabActive = ref();

const selectTab = (param) => {
    tabActive.value = param.id;
    emitTab('onClickTab', param);
}

</script>

<template>
    <ul class="flex justify-start items-end  text-black">
        <li v-for="item in items">
            <button @click="selectTab(item)" :class="tabActive === item.id ? 'bg-[#66C4EA] h-11 text-white' : ' bg-transparent'" 
                class="inline-block px-4 py-2 ">
                {{ item.name }}
            </button>
        </li>
    </ul>
    <hr/>
</template>
