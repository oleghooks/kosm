<script>
import List from "@/components/mobile/orders/list.vue";
import Info from "@/components/mobile/orders/info.vue";

export default {
    components: {Info, List},
    data(){
        return {
            currentPage: 0,
            list: [],
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
        listLoad: async function(){
            this.currentPage = 0;
            let url = '/orders.list';
            let response = await fetch(url);
            this.list = await response.json();
        }
    },
    mounted() {
        this.listLoad();
    }
}
</script>

<template>

    <div v-if="currentPage === 1" v-on:click="currentPage = 0" class="back"><</div>
    <list v-if="currentPage === 0" :list="list" :info="info"></list>
    <info v-if="currentPage === 1" :info="info_order"></info>
</template>

<style scoped>

</style>
