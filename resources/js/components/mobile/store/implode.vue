<script>
import List from "@/components/mobile/orders/list.vue";
import Info from "@/components/mobile/store/implode/info.vue";

export default {
    components: {List, Info},
    props: ['back_store_main'],
    data(){
        return{
            currentPage: 0,
            implode_list: [],
            info_order: {}
        }
    },
    methods: {
        info: async function(id){
            this.info_order = {};
            this.currentPage = 1;
            let url = '/orders.info?id='+id;
            let response = await fetch(url);
            this.info_order = await response.json();
        },
        list: async function (){
            this.currentPage = 0;
            let url = '/orders.list';
            let response = await fetch(url);
            this.implode_list = await response.json();
        },
    },
    mounted() {
        this.list();
    }
}
</script>

<template>
    <div v-if="currentPage === 1" v-on:click="currentPage = 0" class="back"><</div>
    <list v-if="currentPage === 0" :list="implode_list" :info="info"></list>
    <info v-if="currentPage === 1" :info="info_order" :back_store_main="back_store_main"></info>
</template>

<style scoped>

</style>
