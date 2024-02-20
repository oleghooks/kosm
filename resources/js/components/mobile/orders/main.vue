<script>
import List from "@/components/mobile/orders/list.vue";
import Info from "@/components/mobile/orders/info.vue";

import useEventsBus from "@/EventBus.js";
import {watch} from "vue";
export default {
    /**
     * emit {
     *     orders.list.load - Загружает список заказов
     *     orders.info[id] - Загружает указанный [id] заказа
     * }
     *
     * **/
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
        const { bus } = useEventsBus();


        watch(()=>bus.value.get('orders.list.load'), (val) => {
            this.listLoad();
        });
        watch(()=>bus.value.get('orders.info'), (val) => {
            let [item] = val ?? 0;
            this.info(item);
        });
        this.listLoad();
    }
}
</script>

<template>

    <div v-if="currentPage === 1" v-on:click="currentPage = 0; this.listLoad()" class="back"><</div>
    <list v-if="currentPage === 0" :list="list" :info="info"></list>
    <info v-if="currentPage === 1" :info="info_order"></info>
</template>

<style scoped>

</style>
