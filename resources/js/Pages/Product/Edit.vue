<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    product: Object,
});

const form = useForm({
    title: props.product.title,
    description: props.product.description,
    path: props.product.path,
});

const submit = () => {
    form.put(route('products.update', props.product.id));
};

</script>

<template>
    <Head title="Product edit" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit product
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div className="flex items-center justify-between mb-6">
                            <Link
                                className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none"
                                :href="route('service')"
                            >
                                Back
                            </Link>
                        </div>

                        <form name="createForm" @submit.prevent="submit">
                                <div className="flex flex-col">
                                    <div>
                                        <InputLabel for="title" value="Название" />

                                        <TextInput
                                            id="title"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.title"
                                            required
                                            autofocus
                                            autocomplete="title"
                                        />

                                        <InputError class="mt-2" :message="form.errors.title" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="description" value="Примечание" />

                                        <TextInput
                                            id="description"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.description"
                                            required
                                            autocomplete="description"
                                        />

                                        <InputError class="mt-2" :message="form.errors.description" />
                                    </div>

                                    <div class="mt-4">
                                        <InputLabel for="path" value="Картинка" />

                                        <TextInput
                                            id="path"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.path"
                                            required
                                            autocomplete="path"
                                        />

                                        <InputError class="mt-2" :message="form.errors.path" />
                                    </div>


                                </div>
  
                                <div className="mt-4">
                                    <button
                                        type="submit"
                                        className="px-6 py-2 font-bold text-white bg-green-500 rounded"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>