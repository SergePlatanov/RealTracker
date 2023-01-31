<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { useState } from '@/store';
import { computed } from 'vue';

const props = defineProps({
    technos: Array,
    products: Array,
});

const form = useForm({});

const store = useState();

function destroy(id) {
    if(confirm("Are you sure to delete this techno?")){
        form.delete(route('technos.destroy', id));
    }
}

const proxyTechnos = computed(() => props.technos.filter(el => el.product_id === store.state.curProductID));

function getCurProductTitle() {
    console.log("curProductID:" + store.state.curProductID);
    var p= props.products.find(function isIDEquals(el) { return el.id === store.state.curProductID });
    return p ? p.title : "";
}

function selectRow(id) {
    store.setState("CUR_TECHNO_ID", id);
}

</script>


<template>
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="table-responsive select-none">
                    <table v-if="proxyTechnos.length > 0" class="ml-5 table-auto border-collapse border border-slate-400">
                        <thead>
                            <tr class="bg-blue-100">
<!--                                <th class="w-10 border border-slate-300">ID</th> -->
<!--                                <th class="w-auto border border-slate-300">Product</th> -->
                                <th class="w-auto border border-slate-300">Title</th>
                                <th class="w-28 border border-slate-300">Order</th>
                                <th class="border border-slate-300">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(techno,key) in proxyTechnos" :key="key" @click="selectRow(techno.id)" :class="{'bg-green-200': (techno.id == store.state.curTechnoID)}">
<!--                                <td class="border border-slate-300 text-center">{{ techno.id }}</td>  -->
<!--                                <td class="border border-slate-300 px-3 text-center">{{ getCurProductTitle() }}</td> -->
                                <td class="border border-slate-300 px-3">{{ techno.title }}</td>
                                <td class="border border-slate-300 text-center">{{ techno.order }}</td>
                                <td class="border border-slate-300">
                                    <Link
                                        tabIndex="1"
                                        class="w-20 px-4 py-2 text-sm text-white bg-blue-500 rounded"
                                        :href="route('technos.edit', techno.id)"
                                    >
                                        Edit
                                    </Link>

                                    <button
                                        @click="destroy(techno.id)"
                                        tabIndex="-1"
                                        type="button"
                                        class="w-20 px-4 py-2 text-sm text-white bg-red-500 rounded"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else colspan="4" align="center">
                        No technos found for {{getCurProductTitle()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>