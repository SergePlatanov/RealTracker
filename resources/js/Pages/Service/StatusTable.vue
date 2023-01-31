<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { useState } from '@/store';
import { computed } from 'vue';

const props = defineProps({
    status: Array,
    technos: Array,
});

const form = useForm({});

const store = useState();

const proxyStatus = computed(() => props.status.filter(el => el.techno_id === store.state.curTechnoID));

function destroy(id) {
    if(confirm("Are you sure to delete this status?")){
        form.delete(route('status.destroy', id));
    }
}

</script>


<template>
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table v-if="proxyStatus.length > 0" class="ml-5 table-auto border-collapse border border-slate-400">
                        <thead>
                            <tr class="bg-blue-100">
<!--                                <th class="w-10 border border-slate-300">ID</th> -->
                                <th class="w-auto border border-slate-300">Title</th>
                                <th class="w-28 border border-slate-300">Level</th>
                                <th class="w-28 border border-slate-300">Order</th>
                                <th class="border border-slate-300">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(st,key) in proxyStatus" :key="key">
<!--                                <td class="border border-slate-300 text-center">{{ st.id }}</td>  -->
                                <td class="border border-slate-300 px-3">{{ st.title }}</td>
                                <td class="border border-slate-300 px-3">{{ st.level }}</td>
                                <td class="border border-slate-300 text-center">{{ st.order }}</td>
                                <td class="border border-slate-300">
                                    <Link
                                        tabIndex="1"
                                        class="w-20 px-4 py-2 text-sm text-white bg-blue-500 rounded"
                                        :href="route('status.edit', st.id)"
                                    >
                                        Edit
                                    </Link>

                                    <button
                                        @click="destroy(st.id)"
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
                        No status found
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>