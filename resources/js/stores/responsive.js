import { defineStore } from 'pinia';
import axios from 'axios';

export const useResponsiveStore = defineStore('responsive', {
    state: () => ({
        filtersOpened: false,
        screenWidth: window.innerWidth
    }),
    actions: {
        toggleFilters() {
            this.filtersOpened = !this.filtersOpened;
        },
    },
    getters: {

    },
});

window.addEventListener('resize', e => {
    useResponsiveStore().screenWidth = window.innerWidth;
    console.log(useResponsiveStore().screenWidth);
});
