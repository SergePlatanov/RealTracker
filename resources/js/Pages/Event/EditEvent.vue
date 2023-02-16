<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Combobox from '@/Components/Combobox.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useState } from '@/store';
import Datepicker from '@vuepic/vue-datepicker';
import { ru } from 'date-fns/locale';
import '@vuepic/vue-datepicker/dist/main.css';

const store = useState();

const props = defineProps({
    IsEdit: Boolean,
    id: Number,
    date: String,
    product_id: Number,
    sn_n: Number,
    sn_m: Number,
    sn_p: Number,
    description: String,
    techno_id: Number,
    status_id: Number,
    active: Boolean,
});

const date = ref(new Date());
const format = (date) => {
    console.log("format:"+date);
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    form.date= date.toISOString().split("T")[0];
    return (day > 9 ? day : "0"+day)+"-"+(month > 9 ? month : "0"+month)+"-"+year;
}

const form = useForm({
    date: props.date,
    product_id: props.product_id,
    sn_n: props.sn_n,
    sn_m: props.sn_m,
    sn_p: props.sn_p,
    description: props.description,
    techno_id: props.techno_id,
    status_id: props.status_id,
    active: props.active,
});

function getTechnoNames(items) {
    const arr= [];
    items.forEach((el) => { arr.push(el.title); });
    return arr;
}

const proxyTechnoID = computed({
    get() {
        var Index= false;
        var t= store.state.curTechnos.find((el, key) =>  {
            if (el.id === form.techno_id)  {
                Index= key;
                return true;
            } else {
                return false;
            }
        });
        return Index;
    },
    set(val) {
        form.techno_id= store.state.curTechnos[val].id;
    },
});

function getStatusNames() {
    const arr= [];
    const sts= store.state.curStatus.filter(el => el.techno_id == form.techno_id);
    sts.forEach((el) => { arr.push(el.title); });
    return arr;
}

const proxyStatusID = computed({
    get() {
        const sts= store.state.curStatus.filter(el => el.techno_id == form.techno_id);
        var Index= false;
        var t= sts.find((el, key) =>  {
            if (el.id === form.status_id)  {
                Index= key;
                return true;
            } else {
                return false;
            }
        });
        return Index;
    },
    set(val) {
        const sts= store.state.curStatus.filter(el => el.techno_id == form.techno_id);
        form.status_id= sts[val].id;
    },
});

const submit = () => {
    if (form.description === null) form.description= '';
    if (props.IsEdit) {
        form.put(route('events.update', props.id), {
//            preserveScroll: (page) => Object.keys(page.props.errors).length,
//            preserveScroll: true,
        });
    } else {
        form.post(route('events.store'), {
            onFinish: () => form.reset('description'),
//            preserveScroll: (page) => Object.keys(page.props.errors).length,
//            preserveScroll: true,
        });
    }
};

</script>

<template>
    <Head :title="props.IsEdit ? 'Edit event':'Create new event'" />
    <AuthenticatedLayout>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">    
            <form @submit.prevent="submit">
                <div class="flex flex-col">
                    <div>
                        <InputLabel for="date" value="Дата" />
                        <Datepicker
                            id="date"
                            v-model="date"
                            :format="format"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.date" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="sn_p"  value="Серийный номер" />
                        <div class="flex flex-row">
                            <TextInput
                                id="sn_p"
                                type="text"
                                class="mt-1 basis-1/4 w-full"
                                v-model="form.sn_p"
                                autocomplete="sn_p"
                            />
                            <TextInput
                                id="sn_m"
                                type="text"
                                class="mt-1 basis-1/4 w-full"
                                v-model="form.sn_m"
                                autocomplete="sn_m"
                            />
                            <TextInput
                                id="sn_n"
                                type="text"
                                class="mt-1 basis-1/2 w-full"
                                v-model="form.sn_n"
                                required
                                autocomplete="sn_n"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.sn_p" />
                        <InputError class="mt-2" :message="form.errors.sn_m" />
                        <InputError class="mt-2" :message="form.errors.sn_n" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="description" value="Описание" />
                        <TextArea
                            id="description"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.description"
                            autocomplete="description"
                            rows="3"
                        />
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="techno_id" value="Техпроцес" />
                        <Combobox 
                            id="techno_id"
                            class= "mt-1" 
                            :items="getTechnoNames(store.state.curTechnos)" 
                            v-model="proxyTechnoID"
                            :selected="proxyTechnoID"
                            required
                            autocomplete="techno_id"
                        />

                        <InputError class="mt-2" :message="form.errors.techno_id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="status_id" value="Статус" />
                        <Combobox 
                            id="status_id"
                            class= "mt-1" 
                            :items="getStatusNames()" 
                            v-model="proxyStatusID"
                            :selected="proxyStatusID"
                            autocomplete="status_id"
                        />

                        <InputError class="mt-2" :message="form.errors.techno_id" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{props.IsEdit ? "Сохранить" : "Добавить"}}
                        </PrimaryButton>
                        <Link
                            :href="route('product', store.state.curProductID)"
                            method="get"
                            as="button"
                            class="ml-3 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >Cancel
                        </Link>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
