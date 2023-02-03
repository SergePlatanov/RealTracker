<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import Combobox from '@/Components/Combobox.vue';
    import EventsTable from '@/Pages/Event/EventsTable.vue';
    import ProductCard from '@/Components/ProductCard.vue';
    import { ref, computed, onMounted } from 'vue';
    import { useState } from '@/store';

    const props= defineProps({
        product: Object,
        tables: Array,
        technos: Array,
        status: Array,
        numbers: Array,
    });

    const store = useState();

    function getFNumber(sn) {
        var num= props.numbers.findLast(function (el) {
            return el.product_id === props.product.id && el.serial == sn;
        });

        return num ? num.factory : 's/n:'+sn;
    }

    function getEvents(sn) {
        var evt= props.events.filter(function (el) {
            return el.product_id === props.product.id && el.sn_n == sn;
        });
        return evt;
    }

    function getUniqueSN() {
        const arr= [];
        props.events.forEach(el => {
            if (el.product_id === props.product.id && arr.indexOf(el.sn_n) < 0) arr.push(el.sn_n);
        });
        return arr;
    }

    onMounted(() => {
        store.setState("CUR_PRODUCT_ID", props.product.id);
    });

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="font-semibold text-xl text-gray-800 leading-tight">
                <ProductCard :name="product.title" :description="product.description" :img="product.path" />
            </div>
        </template>

        <div v-if="tables[props.product.id][0].length" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="(events, sn) in tables[props.product.id]" class="bg-white overflow-hidden shadow-sm sm:rounded-lg" :key="sn">
                    <div class="flex justify-between items-center pt-5">
                        <h3 class="font-semibold text-lg text-gray-800 m-4 leading-tight">{{getFNumber(sn)}}</h3>
                        <div>
                        <Link
                            class="px-3 py-2 mr-2 text-4xl text-black rounded-md focus:outline-none"
                            :href="route('events.create')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                        <Link
                            class="px-3 py-2 mr-2 text-4xl text-black rounded-md focus:outline-none"
                            :href="route('events.create')"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                            <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                            </svg>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 fill-red-600">
                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                            </svg>

                        </Link>
                        </div>

                    </div>
                    <EventsTable 
                        :product_id="props.product.id"
                        :events="events" 
                        :technos="technos"
                        :status="status"
                    >
                    </EventsTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
