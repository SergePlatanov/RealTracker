<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, useForm, router } from '@inertiajs/vue3';
    import Combobox from '@/Components/Combobox.vue';
    import EventsTable from '@/Pages/Event/EventsTable.vue';
    import ProductCard from '@/Components/ProductCard.vue';
    import { ref, computed, onMounted, onBeforeUnmount, onUnmounted } from 'vue';
    import { useState } from '@/store';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';

    const store = useState();

    const props= defineProps({
        product: Object,
        tables: Object,
        technos: Array,
        status: Array,
    });
/*
    const saveSearch= useRemember({
        text: '',
        sn: '',
        fn: '',
    }, `Product:${props.product.id}`);*/

    const search= useForm({
        text: '',
        sn: '',
        fn: '',
    });

    function saveFilter()
    {
        var arr= [];
        arr[props.product.id]= {
                text: search.text,
                sn: search.sn,
                fn: search.fn,
            };
        store.setState("PRODUCT_FILTER", arr);
    }

    function getFilter()
    {
        const arr= store.state.ProductFilter;
        if (arr[props.product.id]) {
            search.text = arr[props.product.id].text;
            search.sn   = arr[props.product.id].sn;
            search.fn   = arr[props.product.id].fn;
        }
    }

    const factoryNumberID= ref(null);

    const searchTable= ref(null);

    const editEnableSN= ref(store.state.ProductEditEnableSN);

    let viewTable = computed(() => {
        return searchTable.value !== null;
    });

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

    function getFNumber(sn) {
        var fn= 's/n:'+sn;
        for (var key in props.tables) {
//            console.log('sn_n: ' + props.tables[key][0].sn_n);
            if (props.tables[key][0].sn_n == sn) {
                props.tables[key].forEach((el) => {
                    if (el.techno_id == factoryNumberID.value) fn= el.description ? el.description : 's/n:'+sn;
                })
            }
        }
        return fn;
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
        store.setState("CUR_PRODUCT_NAME", props.product.title);
        store.setState("CUR_TECHNOS", props.technos);
        store.setState("CUR_STATUS", props.status);
        setTimeout(() => window.scrollTo(0, store.state.ProductScroll), 300);
//        editEnableSN.value= router.restore('product-editEnableSN');
        getFilter();
        onSearch();

        var t= props.technos.find(function (el) {
            return el.title == 'установка заводского номера';
        });
        factoryNumberID.value= t ? t.id : null;
    });

    onBeforeUnmount(() => {
        store.setState("PRODUCT_SCROLL", window.top.scrollY);
        console.log("onBeforeUnmount Product:" + window.top.scrollY);
    });

    let removeDeleteEventListener = router.on('deleteEvent', (event) => {
        onSearch();
    });

    onUnmounted(() => {
        console.log("onUnmounted Product");
//        removeDeleteEventListener();
    });

    const isFiltered= ref(false);
    function onSearch() {
        console.log("onSearch");
        if (!search.text && !search.sn && !search.fn) {
            searchTable.value= props.tables;
            isFiltered.value= false;
            saveFilter();
            return;
        }

        var objsn= {};
        if (search.sn) {
            for (var key in props.tables) {
                if (props.tables[key][0].sn_n == parseInt(search.sn)) {
                    objsn[key]= props.tables[key];
                    break;
                }
            }
        } else {
            objsn= props.tables;
        }
        var objfn= {};
        if (search.fn) {
            for (var key in objsn) {
                var fn= getFNumber(objsn[key][0].sn_n);
                if (fn.includes(search.fn)) {
                    if (!(key in objfn)) {
                        objfn[key]= objsn[key];
                    }
                }
            }
        } else {
            objfn= objsn;
        }   

        var objtx= {};
        if (search.text) {
            for (var key in objfn) {
                const a= objfn[key].every(el => {
                    if (el.description && el.description.includes(search.text)) {
                        if (!(key in objtx)) {
                            objtx[key]= objfn[key];
                            return false;
                        }
                    }
                    return true;
                });
            }
            searchTable.value= objtx;
        } else {
            searchTable.value= objfn;
        }
        isFiltered.value= true;
        saveFilter();
    }

    function onSearchReset() {
        search.text= ''; 
        search.sn= '';
        search.fn= '';
        console.log("onSearchReset");
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
            <div class="flex justify-between font-semibold text-xl text-gray-800 leading-tight">
                <ProductCard :name="product.title" :description="product.description" :img="'/storage/'+product.path" />
                <Link
                    class="px-3 py-2 mr-2 focus:outline-none"
                    :href="route('events.create')"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                    </svg>
                </Link>
            </div>
        </template>
        
        <div class="sticky top-0 w-full mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex max-w-7xl mx-auto my-4 sm:px-6 lg:px-8">
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
                <h3 class="font-semibold text-lg ml-4 text-gray-800 leading-tight" :class="{'text-green-600': isFiltered}">Фильтр</h3>
                <div class= "flex px-4 max-w-7xl ml-8 sm:px-6 lg:px-8" :class="{'text-green-700': isFiltered}">
                    <div class= "flex px-4" v-if="search.sn">{{'сн:' + search.sn}}</div>
                    <div class= "flex px-4" v-if="search.fn">{{'зн:' + search.fn}}</div>
                    <div class= "flex px-4" v-if="search.text">{{'текс:' + search.text}}</div>
                    <svg v-if="isFiltered" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 cursor-pointer" :class="{'fill-green-600': isFiltered}" @click="onSearchReset()">
                        <path d="m12 10.93 5.719-5.72c.146-.146.339-.219.531-.219.404 0 .75.324.75.749 0 .193-.073.385-.219.532l-5.72 5.719 5.719 5.719c.147.147.22.339.22.531 0 .427-.349.75-.75.75-.192 0-.385-.073-.531-.219l-5.719-5.719-5.719 5.719c-.146.146-.339.219-.531.219-.401 0-.75-.323-.75-.75 0-.192.073-.384.22-.531l5.719-5.719-5.72-5.719c-.146-.147-.219-.339-.219-.532 0-.425.346-.749.75-.749.192 0 .385.073.531.219z"/>
                    </svg>
                </div>
            </div>
            <div v-if="viewFilter" class= "p-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center w-full">
                    <InputLabel for="text" class="mr-2" value="Поиск по тексту" />
                    <TextInput id="text" v-model="search.text" @keyup.enter="onSearch()" class="mt-1 border-2 mb-2 basis-1/4 w-full"/>
                </div>
                <div class="flex">
                    <div class="flex items-center mr-4">
                        <InputLabel for="sn" class="mr-2" value="Поиск по серийному номеру" />
                        <TextInput id="sn" v-model="search.sn" @keyup.enter="onSearch()" class="mt-1 mb-2 border-2 basis-1/4 w-full"/>
                    </div>
                    <div class="flex items-center">
                        <InputLabel for="fn" class="mr-2" value="Поиск по заводскому номеру" />
                        <TextInput id="fn" v-model="search.fn" @keyup.enter="onSearch()" class="mt-1 mb-2 border-2 basis-1/4 w-full"/>
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

        <div v-if="viewTable" class="py-12">
            <div class="max-w-7xl bg-white pb-12 mx-auto sm:px-6 lg:px-8 sm:rounded-lg">
                <div v-for="(events, sn) in searchTable" class="overflow-hidden shadow-sm sm:rounded-lg" :key="sn">
                    <div class="flex justify-between items-center pt-5">
                        <h3 class="font-semibold text-lg text-gray-800 m-4 leading-tight">{{getFNumber(sn)}}</h3>
                        <div v-if="store.can('edit event')" class="flex py-2 mr-2">
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
    </AuthenticatedLayout>
</template>
