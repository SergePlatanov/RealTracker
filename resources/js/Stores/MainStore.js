import {defineStore} from 'pinia';
import { ref } from 'vue';

export const useMainStore = defineStore('mainStore', () => {
    const curProductID          = ref(false)
    const curProductName        = ref('')
    const curTechnoID           = ref(false)
    const curTechnos            = ref(null)
    const curStatus             = ref(null)
    const ProductEditEnableSN   = ref(-1)
    const ProductScroll         = ref(0)
    const ProductFilter         = ref([])
    const permissions           = ref([])

    return { curProductID, curProductName, curTechnoID, curTechnos, curStatus, 
             ProductEditEnableSN, ProductScroll, ProductFilter, permissions        }
})
