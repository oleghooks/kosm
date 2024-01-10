import { createApp } from 'vue/dist/vue.esm-bundler';
import MainComp from './components/main.vue';

const app = createApp({
    components: {
        'main-comp' : MainComp,
    }
});

app.mount('#app');
