<script setup>

const props = defineProps({
    count:0,
    title:"",
    bgicon:'bg-red-300'
})

</script>

<template>
    <div class=" p-5 flex items-center rounded-md  bg-slate-200 ">
        <div  :class="'w-20 h-20 flex justify-center items-center rounded-full ' + props.bgicon ">
            <slot/>
        </div>
        <div class="mx-5">
            <h1 class=" text-slate-900 text-3xl">{{ props.count }}</h1>
            <h6 class="text-slate-500 uppercase ">{{props.title}}</h6>
        </div>

    </div>
</template>