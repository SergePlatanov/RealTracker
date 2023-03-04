import { reactive, provide, inject, readonly } from 'vue';

export const stateSymbol = Symbol('state');
//export const createState = () => reactive({ counter: 0 });

export const createState = () => {
    const state = reactive({ 
        curProductID: false,
        curTechnoID: false,
        curTechnos: null,
        curStatus: null,
        ProductEditEnableSN: -1,
        ProductScroll: 0,
        ProductFilter: [],
        permissions: [],
    });
    
    const setState = (key, value) => {
        if (key == "CUR_PRODUCT_ID") state.curProductID= value;
        if (key == "CUR_TECHNO_ID")  state.curTechnoID = value;
        if (key == "CUR_TECHNOS")  state.curTechnos = value;
        if (key == "CUR_STATUS")  state.curStatus = value;
        if (key == "PRODUCT_EDIT_ENABLE_SN")  state.ProductEditEnableSN = value;
        if (key == "PRODUCT_SCROLL")  state.ProductScroll = value;
        if (key == "PERMISSIONS")  state.permissions = value;
        if (key == "PRODUCT_FILTER") {
            const iterator= value.keys();
            for (const key of iterator) {
                state.ProductFilter[key]= value[key];
            }            
        }
    };

    const can = (permission) => {
        return state.permissions.find((el) => el.name == permission) !== undefined;
    }

  
    return { state: readonly(state), setState, can };
}

export const useState = () => inject(stateSymbol);
export const provideState = () => provide(
    stateSymbol, 
    createState()
);

