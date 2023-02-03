<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ProductCard from '@/Components/ProductCard.vue';
    import TechnosTable from '@/Pages/Service/TechnosTable.vue';
    import StatusTable from '@/Pages/Service/StatusTable.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import Combobox from '@/Components/Combobox.vue';
    import { ref, computed, onMounted } from 'vue';
    import { useState } from '@/store';

    const props= defineProps({
        products: Array,
        technos: Array,
        status: Array,
    });

    const form = useForm({});

    const store = useState();
    
    function destroy(id) {
        if (confirm("Are you sure you want to Delete")) {
            form.delete(route('products.destroy', id));
        }
    }    

    function getNames(items) {
        const arr= [];
        items.forEach((el) => { arr.push(el.title); });
        return arr;
    }

    const proxyProdIdx = computed({
        get() {
            return props.products.findIndex(function isIDEquals(el) { return el.id === store.state.curProductID });
        },
        set(val) {
            if (props.products[val].id != store.state.curProductID) store.setState("CUR_TECHNO_ID", false);
            store.setState("CUR_PRODUCT_ID", props.products[val].id);
        },
    });

    function getTechnoTitle(id) {
        var t= props.technos.find(el => el.id === id);
        return t ? t.title : "техпроцесс не выбран";
    }

//    const getProductName = computed(() => proxyProdIdx.value === false ? "none" : (props.products[proxyProdIdx.value].title + ':' + getProductID.value));
    
    onMounted(() => {
        if (store.state.curProductID === false) proxyProdIdx.value= 0;
        console.log("Service onMounted curProductID:" + store.state.curProductID);
    });

</script>

<template>
    <Head title="Service" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Service</h2>
        </template>

        <div class="py-12">
            <div class="ml-8 mb-6">
                <Link
                    class="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none"
                    :href="route('products.create')"
                >
                    Add
                </Link>
            </div>


            <div v-if="products != null" class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
                <div v-for="p in products" class="flex justify-between h-30 px-2 py-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <ProductCard :name="p.title" :description="p.description" :img="p.path" />
                    <div class="flex flex-col justify-around">
                        <Link
                            tabIndex="1"
                            class="w-full px-4 py-2 text-sm text-white bg-blue-500 rounded"
                            :href="route('products.edit', p.id)"
                        >
                            Edit
                        </Link>

                        <button
                            @click="destroy(p.id)"
                            tabIndex="-1"
                            type="button"
                            class="w-full px-4 py-2 text-sm text-white bg-red-500 rounded"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div class="pt-12 ml-5">
            <h3 class="font-semibold text-lg text-gray-800 mb-3 leading-tight">Выбор продукта</h3>
            <Combobox class= "ml-2" v-model:selected="proxyProdIdx" :items="getNames(products)" />
        </div>


        <div class="py-12 flex">

            <div class="h-fit">
                <h3 class="font-semibold text-lg text-gray-800 m-4 leading-tight">Технологический процесс</h3>
                <div class="ml-8 mb-6">
                    <Link
                        class="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none"
                        :href="route('technos.create')"
                    >
                        Add
                    </Link>
                </div>
                <TechnosTable :technos="technos" :products="products"></TechnosTable>
            </div>

            <div class="h-fit">
                <h3 class="font-semibold text-lg text-gray-800 m-4 leading-tight">Статус техпроцесса - {{getTechnoTitle(store.state.curTechnoID)}}</h3>
                <div class="ml-8 mb-6" v-if="store.state.curTechnoID">
                    <Link
                        class="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none"
                        :href="route('status.create')"
                    >
                        Add
                    </Link>
                </div>
                <StatusTable :status="status" :technos="technos"></StatusTable>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
