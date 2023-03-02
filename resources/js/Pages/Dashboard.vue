<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import ProductCard from '@/Components/ProductCard.vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import { onMounted } from 'vue';
    import { useState } from '@/store';

    const store = useState();

    const props= defineProps({
        products: Array,
        permissions: Array,
    });

    const form = useForm({});

    function destroy(id) {
        console.log('delete:'+id);
        if (confirm("Are you sure you want to Delete")) {
            form.delete(route('products.destroy', id));
        }
    }

    onMounted(() => {
        store.setState("PERMISSIONS", props.permissions);
        localStorage.setItem("PERMISSIONS", JSON.stringify(props.permissions));
    });

</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
        </template>

        <div class="py-12">
            <div v-if="products != null" class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
                <div v-for="p in products" class="flex justify-between h-30 px-2 py-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Link :href="route('product', p.id)">
                        <ProductCard :name="p.title" :description="p.description" :img="p.path" />
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
