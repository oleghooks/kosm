import { createApp } from 'vue/dist/vue.esm-bundler';
import MainComp from './components/mobile/main.vue';

const app = createApp({
    components: {
        'main-comp' : MainComp,
    }
});

app.mount('#app');
