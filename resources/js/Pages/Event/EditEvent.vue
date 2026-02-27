<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Combobox from '@/Components/Combobox.vue';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, ref, onBeforeMount } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
//import { Inertia } from "@inertiajs/inertia";
import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
//import { ru } from 'date-fns/locale';
//import '@vuepic/vue-datepicker/dist/main.css';
import {useMainStore} from '@/Stores/MainStore.js';

const mainStore = useMainStore();

const props = defineProps({
    IsEdit: Boolean,
    id: Number,
    form: Object,
    errors: Object,
});
const format = (d) => {
    console.log("format:"+d);
//    form.date= d.toISOString().split("T")[0];
    const day = d.getDate();
    const month = d.getMonth() + 1;
    const year = d.getFullYear();
    return year+"-"+(month > 9 ? month : "0"+month)+"-"+(day > 9 ? day : "0"+day);
}

//const date = ref(props.IsEdit ? props.form.date : format(new Date()));

function getTechnoNames() {
    const arr= [];
    mainStore.curTechnos?.forEach((el) => { if (el.deleted_at == null) arr.push(el.title); });
    return arr;
}

const proxyTechnoID = computed({
    get() {
        var Index= false;
        var t= mainStore.curTechnos?.find((el, key) =>  {
            if (el.id === props.form.techno_id)  {
                Index= key;
                return true;
            } else {
                return false;
            }
        });
        return Index;
    },
    set(val) {
        props.form.techno_id= mainStore.curTechnos[val].id;
    },
});

function getCurStatus()
{
    return mainStore.curStatus?.filter(el => (el.techno_id == props.form.techno_id && el.deleted_at == null));
}

function getStatusNames() {
    const arr= [];
    const sts= getCurStatus();
    sts?.forEach((el) => { arr.push(el.title); });
    return arr;
}

const proxyStatusID = computed({
    get() {
        const sts= getCurStatus();
        var Index= false;
        var t= sts?.find((el, key) =>  {
            if (el.id === props.form.status_id)  {
                Index= key;
                return true;
            } else {
                return false;
            }
        });
        return Index;
    },
    set(val) {
        const sts= getCurStatus();
        props.form.status_id= sts[val].id;
    },
});

var newfile= null;

function FileInput(event)
{
    if (event) {
//        console.log("FileInput:" + event.target.files[0]);
        newfile= event.target.files[0];
    } else {
        console.log("FileInput:" + event);
    }
}
/*
const proxyFile = computed({
    get() {
        console.log("proxyFile get");
        return null;
    },
    set(val) {
        console.log("proxyFile set:" + val);
        form.file= val[0];
    },
});
*/
const submit = () => {
    if (props.form.description === null) props.form.description= '';
    if (props.IsEdit) {
        router.post(route('events.update', props.id), {
            _method: 'put',
            date:           props.form.date,
            product_id:     props.form.product_id,
            sn_n:           props.form.sn_n,
            sn_m:           props.form.sn_m,
            sn_p:           props.form.sn_p,
            description:    props.form.description,
            techno_id:      props.form.techno_id,
            status_id:      props.form.status_id,
            active:         props.form.active,
            file:           newfile,
        });
    } else {
        props.form.file= newfile;
        props.form.post(route('events.store'), {
//            onFinish: () => form.reset('description'),
//            preserveScroll: (page) => Object.keys(page.props.errors).length,
//            preserveScroll: true,
        });
    }
};

onBeforeMount(() => {
    if (!props.IsEdit) props.form.date= format(new Date());
});

</script>

<template>
    <Head :title="props.IsEdit ? 'Edit event':'Create new event'" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form @submit.prevent="submit">
                        <div class="flex flex-col">
                            <div>
                                <InputLabel value="Дата" />
                                <VueDatePicker
                                    v-model="form.date"
                                    model-type="yyyy-MM-dd"
                                    :formats="{ input: 'yyyy-MM-dd', preview: 'yyyy-MM-dd' }"
                                    :time-config="{ enableTimePicker: false }"
                                    text-input
                                />
<!--
                                    :locale="ru_RU"
                                    :text-input="{ maskFormat: 'yyyy-MM-dd' }"
                                    required
                                    autofocus

-->                                
                                <InputError class="mt-2" :message="errors.date" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="sn_p"  value="Серийный номер" />
                                <div class="flex flex-column">
                                    <div class="flex flex-column w-full basis-1/4">
                                        <TextInput
                                            id="sn_p"
                                            type="text"
                                            class="mt-1 w-full"
                                            v-model="form.sn_p"
                                            autocomplete="sn_p"
                                        />
                                        <InputError class="mt-2" :message="errors.sn_p" />
                                    </div>
                                    <div class="flex flex-column w-full basis-1/4">
                                        <TextInput
                                            id="sn_m"
                                            type="text"
                                            class="mt-1 w-full"
                                            v-model="form.sn_m"
                                            autocomplete="sn_m"
                                        />
                                        <InputError class="mt-2" :message="errors.sn_m" />
                                    </div>
                                    <div class="flex flex-column w-full basis-1/4">
                                        <TextInput
                                            id="sn_n"
                                            type="text"
                                            class="mt-1 w-full"
                                            v-model="form.sn_n"
                                            required
                                            autocomplete="sn_n"
                                        />
                                        <InputError class="mt-2" :message="errors.sn_n" />
                                    </div>
                                </div>
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
                                <InputError class="mt-2" :message="errors.description" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="techno_id" value="Техпроцес" />
                                <Combobox 
                                    id="techno_id"
                                    class= "mt-1" 
                                    :items="getTechnoNames()" 
                                    v-model:selected="proxyTechnoID"
                                    required
                                    autocomplete="techno_id"
                                />
                                <InputError class="mt-2" :message="errors.techno_id" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="status_id" value="Статус" />
                                <Combobox 
                                    id="status_id"
                                    class= "mt-1" 
                                    :items="getStatusNames()" 
                                    v-model:selected="proxyStatusID"
                                    autocomplete="status_id"
                                />

                                <InputError class="mt-2" :message="errors.status_id" />
                            </div>

                            <div class="mt-4">
                                <label v-if="props.IsEdit && props.form.file">old file: {{props.form.file}}</label>
                                <input type="file" @input="FileInput($event)" />
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                </progress>

                                <InputError class="mt-2" :message="errors.file" />
                            </div>


                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{props.IsEdit ? "Сохранить" : "Добавить"}}
                                </PrimaryButton>
                                <Link
                                    :href="route('product', mainStore.curProductID)"
                                    method="get"
                                    as="button"
                                    class="ml-3 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >Cancel
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
