import { App } from 'vue';
// components
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Simplebar from 'simplebar-vue';

export default {
    install(app: App) {
        app.component('AuthenticatedLayout', AuthenticatedLayout);
        app.component('Simplebar', Simplebar);
    }
};
