<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ProductCard from '@/Components/ProductCard.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';

    defineProps({
        products: Array,
    });

    const form = useForm({});

    function destroy(id) {
        console.log('delete:'+id);
        if (confirm("Are you sure you want to Delete")) {
            form.delete(route('products.destroy', id));
        }
    }    
</script>

<template>
    <Head title="Products Control" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products Control</h2>
        </template>

        <div class="py-12">
<!--        
            <form :action="route('products.create')" method="get">
                <PrimaryButton type="submit" class="ml-4">Add Product</PrimaryButton>
            </form>        
-->
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
    </AuthenticatedLayout>
</template>
