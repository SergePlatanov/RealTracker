<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    path: '',
});

const submit = () => {
    form.post(route('products.store'), {
        onFinish: () => form.reset('title', 'description', 'path'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create new product" />

        <form @submit.prevent="submit">
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
                <InputLabel for="description" value="Описание" />

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

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Добавить
                </PrimaryButton>
                <Link
                    :href="route('products.index')"
                    method="get"
                    as="button"
                    class="ml-3 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >Cancel
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
