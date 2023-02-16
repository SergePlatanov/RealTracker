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
    });
    
    const setState = (key, value) => {
        if (key == "CUR_PRODUCT_ID") state.curProductID= value;
        if (key == "CUR_TECHNO_ID")  state.curTechnoID = value;
        if (key == "CUR_TECHNOS")  state.curTechnos = value;
        if (key == "CUR_STATUS")  state.curStatus = value;
        if (key == "PRODUCT_EDIT_ENABLE_SN")  state.ProductEditEnableSN = value;
        if (key == "PRODUCT_SCROLL")  state.ProductScroll = value;
    };
  
    return { state: readonly(state), setState };
}

export const useState = () => inject(stateSymbol);
export const provideState = () => provide(
    stateSymbol, 
    createState()
);
