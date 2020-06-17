import 'focus-visible';
import Vue from 'vue';
import CompareApp from "./CompareApp";

document.addEventListener('DOMContentLoaded', () => {
    new Vue({
        el: document.getElementById('compare-app'),
        components: {
            'compare-app': CompareApp,
        },
    });
});
