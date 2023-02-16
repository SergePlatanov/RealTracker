<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import Combobox from '@/Components/Combobox.vue';
    import EventsTable from '@/Pages/Event/EventsTable.vue';
    import ProductCard from '@/Components/ProductCard.vue';
    import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
    import { useState } from '@/store';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';

    const store = useState();

    const search= useForm({
        text: '',
        sn: '',
        fn: '',
    });

    const searchTable= ref(null);

    const editEnableSN= ref(store.state.ProductEditEnableSN);

    function swEdit(sn) {
        if (editEnableSN.value != sn) {
            editEnableSN.value= sn;
        } else {
            editEnableSN.value= -1;
        }
        store.setState("PRODUCT_EDIT_ENABLE_SN", editEnableSN.value);
//        router.remember(editEnableSN.value, 'product-editEnableSN');

//        console.log('swEdit: '+ editEnableSN.value + '  t:' + (typeof editEnableSN.value) + ' - ' + (typeof sn));
    };
    
    function getEditable(sn) {
//        console.log('getEditable: '+ editEnableSN.value + ' ' + sn + '  t:' + (typeof editEnableSN.value) + ' - ' + (typeof sn));
        return editEnableSN.value === sn;
    };

    const props= defineProps({
        product: Object,
        tables: Array,
        technos: Array,
        status: Array,
        numbers: Array,
    });

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

    function getLastSN(events) {
        const el= events[events.length-1];
        return el.sn_p * 10000000 + el.sn_m * 10000 + el.sn_n
    }

    onMounted(() => {
        store.setState("CUR_PRODUCT_ID", props.product.id);
        store.setState("CUR_TECHNOS", props.technos);
        store.setState("CUR_STATUS", props.status);
        setTimeout(() => window.scrollTo(0, store.state.ProductScroll), 300);
//        editEnableSN.value= router.restore('product-editEnableSN');
//        console.log("onMounted:" + store.state.ProductScroll);
        onSearch();
    });

    onBeforeUnmount(() => {
        store.setState("PRODUCT_SCROLL", window.top.scrollY);
//        console.log("onBeforeUnmount:" + window.top.scrollY);
    });

    const isFiltered= ref(false);
    function onSearch() {
        if (!search.text && !search.sn && !search.fn) {
            searchTable.value= props.tables[props.product.id];
            isFiltered.value= false;
            return;
        }

        var objn= {};
        if (search.sn) {
            for (var key in props.tables[props.product.id]) {
                if (props.tables[props.product.id][key][0].sn_n == parseInt(search.sn)) {
                    objn[key]= props.tables[props.product.id][key];
                }
            }
        }
        if (search.fn) {
            for (var key in props.tables[props.product.id]) {
                if (getFNumber(props.tables[props.product.id][key][0].sn_n).includes(search.fn)) {
                    if (!(key in objn)) {
                        objn[key]= props.tables[props.product.id][key];
                    }
                }
            }
        }
        if (!search.sn && !search.fn) {
            objn= props.tables[props.product.id];
        }   

        var obj= {};
        if (search.text) {
            for (var key in objn) {
                const a=objn[key].filter((el, idx) => {
                        return el.description.includes(search.text);
                    });
                if (a.length) {
                    obj[key]= a;
                }
            }
            searchTable.value= obj;
        } else {
            searchTable.value= objn;
        }
        isFiltered.value= true;
    }

    function onSearchReset() {
        search.text= ''; 
        search.sn= '';
        search.fn= '';
        onSearch();
    }

    const viewFilter= ref(false);
    function onViewFilter() {
        viewFilter.value= !viewFilter.value
    }

</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<template>
    <Head title="Product" />

    <AuthenticatedLayout>
        <template #header>
            <div class="font-semibold text-xl text-gray-800 leading-tight">
                <ProductCard :name="product.title" :description="product.description" :img="product.path" />
            </div>
        </template>
        
        <div class="sticky top-0 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex m-4">
                <div @click="onViewFilter()" class="cursor-pointer">
                    <div v-if="viewFilter">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" :class="{'fill-green-600': isFiltered}">
                        <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" :class="{'fill-green-600': isFiltered}">
                        <path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 01-1.06-1.06l7.5-7.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-semibold text-lg ml-4 text-gray-800 leading-tight">Фильтр</h3>
            </div>
            <div v-if="viewFilter" class= "p-4 my-2">
                <div class="flex items-center w-full">
                    <InputLabel for="text" class="mr-2" value="Поиск по тексту" />
                    <TextInput id="text" v-model="search.text" class="mt-1 border-2 mb-2 basis-1/4 w-full"/>
                </div>
                <div class="flex">
                    <div class="flex items-center mr-4">
                        <InputLabel for="sn" class="mr-2" value="Поиск по серийномуномеру" />
                        <TextInput id="sn" v-model="search.sn" class="mt-1 mb-2 border-2 basis-1/4 w-full"/>
                    </div>
                    <div class="flex items-center">
                        <InputLabel for="fn" class="mr-2" value="Поиск по заводскому номеру" />
                        <TextInput id="fn" v-model="search.fn" class="mt-1 mb-2 border-2 basis-1/4 w-full"/>
                    </div>
                </div>
                <div class="flex">
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        @click="onSearch()"
                    >
                        Поиск
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center px-4 py-2 ml-4 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        @click="onSearchReset()"
                    >
                        Сброс
                    </button>
                </div>
            </div>
        </div>

        <div v-if="searchTable" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="(events, sn) in searchTable" class="bg-white overflow-hidden shadow-sm sm:rounded-lg" :key="sn">
                    <div class="flex justify-between items-center pt-5">
                        <h3 class="font-semibold text-lg text-gray-800 m-4 leading-tight">{{getFNumber(sn)}}</h3>
                        <div class="flex py-2 mr-2">
                            <Link
                                class="px-3 text-4xl text-black rounded-md focus:outline-none"
                                :href="route('events.create', getLastSN(events))"
                                as="button"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                                </svg>
                            </Link>
                            <div
                                class="px-3 text-4xl text-black rounded-md focus:outline-none cursor-pointer"
                                @click="swEdit(sn)"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" :class="{'fill-green-600': getEditable(sn)}">
                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <EventsTable 
                        :product_id="props.product.id"
                        :events="events" 
                        :technos="technos"
                        :status="status"
                        :editEnable="getEditable(sn)"
                    >
                    </EventsTable>
                </div>
            </div>
        </div>
        <div v-else class="px-3">
            <Link
                class="px-3 py-2 mr-2 focus:outline-none"
                :href="route('events.create')"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                </svg>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
