<script>
import Implode from "@/components/mobile/store/implode.vue";

export default {
    components: {Implode},
    data(){
        return{
            currentPage: 0,
            items: [],
        }
    },
    methods: {
        back: function (){
            this.list();
        },
        list: async function (){
            this.currentPage = 0;
            let url = '/store.list';
            let response = await fetch(url);
            this.items = await response.json();
        }
    }
}
</script>

<template>
    <div v-if="currentPage === 0">
        <button class="button-blue button" v-on:click="currentPage = 1;">Загрузить товары</button>

    </div>
    <implode  v-if="currentPage === 1" :back_store_main="back" />
</template>

<style scoped>

</style>
