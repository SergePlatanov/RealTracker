import { reactive, provide, inject, readonly } from 'vue';

export const stateSymbol = Symbol('state');
//export const createState = () => reactive({ counter: 0 });

export const createState = () => {
    const state = reactive({ 
        curProductID: false,
        curTechnoID: false,
    });
    
    const setState = (key, value) => {
        if (key == "CUR_PRODUCT_ID") state.curProductID= value;
        if (key == "CUR_TECHNO_ID")  state.curTechnoID = value;
    };
  
    return { state: readonly(state), setState };
}

export const useState = () => inject(stateSymbol);
export const provideState = () => provide(
    stateSymbol, 
    createState()
);
