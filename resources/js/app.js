/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');
//window.alertSound = require('../sound/alert_patient.wav')
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./components', true, /\.vue$/i)
files.keys().forEach(function(key) {
    let name = key.split('/').pop().split('.')[0];
    let componentName =  name.split(/(?=[A-Z])/).map(s => s.toLowerCase()).join('-').split('.')[0];
    Vue.component(componentName, files(key).default);
});


import { VueMaskDirective } from 'v-mask'
Vue.directive('mask', VueMaskDirective )
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//window.alertSound = new Audio(require('../sound/alert_patient.wav'))
const app = new Vue({
    el: '#app',
});
