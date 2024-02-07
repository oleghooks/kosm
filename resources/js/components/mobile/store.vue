<script>
import Implode from "@/components/mobile/store/implode.vue";
import List from "@/components/mobile/store/list.vue";
import Info from "@/components/mobile/store/info.vue";
import {post} from "@/post.js";

export default {
    components: {Info, List, Implode},
    data(){
        return{
            currentPage: 0,
            items: [],
            store_info: {}
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
        },
        info: async function(id){
            let response = await post('/store.info', {id: id});
            this.store_info = await response.json();
            this.currentPage = 2;
        }
    },
    mounted() {
        this.list();
    }
}
</script>

<template>
    <div v-if="currentPage === 0">
        <button class="button-blue button" v-on:click="currentPage = 1;">Загрузить товары</button>
        <list :items="items" :info="info" />
    </div>
    <implode  v-if="currentPage === 1" :back_store_main="back" />
    <div v-if="currentPage === 2" v-on:click="back" class="back"><</div>
    <info v-if="currentPage === 2" :info="store_info"  />
</template>

<style scoped>

</style>
