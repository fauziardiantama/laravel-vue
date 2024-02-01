/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import router from './router/index';
import store from './store/index';
import CoreuiVue from '@coreui/vue';
import CIcon from '@coreui/icons-vue';
import { iconsSet as icons } from '@/assets/icons';
import DocsExample from '@/components/DocsExample.vue';

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

const app = createApp({});
//tambah store (vuex?)
app.use(store);
//tambah router
app.use(router);
//tambah coreui
app.use(CoreuiVue);
//tambah icons dari folder assets
app.provide('icons', icons);
//tambah CIcon ??
app.component('CIcon', CIcon);
//tambah DocsExample dari folder components untuk ??
app.component('DocsExample', DocsExample);


// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
