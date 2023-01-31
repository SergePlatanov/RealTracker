<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:selected']);

const props = defineProps({
//    items: Array,
    selected: String,
    value: {
        default: null,
    },
});

const proxySelected = computed({
    get() {
        return props.selected;
    },

    set(val) {
        emit('update:selected', val);
    },
});
</script>

<template>
<!--        <select v-model="proxySelect" :value="modelValue">
            <option v-for="(item, key) in items" value="item" :key="key">{{item}}</option>
        </select>
-->
        <input
            :value="value"
             v-model="proxySelected"
        />        
</template>