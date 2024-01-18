<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

import {watch} from "vue";
import Full_info from "@/components/right-side/info/full_info.vue";
import ProvideInfo from "@/components/right-side/info/provide-info.vue";
import Posts from "@/components/right-side/info/posts.vue";
import Cart from "@/components/right-side/info/cart.vue";
export default {
    props: ['cart_add_main', 'cart_items'],

    components: {Cart, Posts, ProvideInfo, Full_info},
    watch: {
        cart_items: {
            handler(newCartItems){
                this.calcCartItems();
            },
            deep: true
        }
    },
    data(){
        return {
            infoItem: {
                items: {},
            },
            infoProvider: {

            },
            currentInfoItem: false,
            currentOrder: 'popular',
            currentId: 0,
            count_cart: 0,
            type: 'posts'
        }
    },
    methods: {
        calcCartItems: function(){
            this.count_cart = 0;
            if(this.currentId > 0){
                this.cart_items.forEach(item => {
                    if(item.provide_id === this.currentId)
                        this.count_cart++;
                });
            }
        },
        info: async function(id){
            this.currentId = id;
            this.calcCartItems();
            this.infoItem.items = [];
            this.infoProvider = {};
            let url = '/api/providers.info?id='+id+'&order='+this.currentOrder;
            let response = await fetch(url);
            response = await response.json()
            this.infoItem.items = response.items;
            this.infoProvider = response.provider;
        },
        cart_add: function(item_id, attach_index, count, price){
            this.cart_add_main(
                item_id,
                attach_index,
                count,
                price,
                this.currentId
            );
        },
        changeCurrentInfoItem: function(id){
            this.currentInfoItem = id;
        },
        changeOrder: async function(type){
            this.currentOrder = type;
            await this.info(this.currentId);
        }
    },
    mounted() {
        const { bus } = useEventsBus()

        watch(()=>bus.value.get('info'), (val) => {
            // destruct the parameters
            const [id] = val ?? 0
            this.info(id);
        })
        watch(()=>bus.value.get('currentInfoItemFalse'), (val) => {
            // destruct the parameters
            this.currentInfoItem = false;
        })
    }
}
</script>

<template>
    <provide-info :infoProvider="infoProvider"/>

    <div class="provider-menu" v-if="infoItem.items.length > 0">
        <div v-on:click="type = 'posts'" :class="{'active': type === 'posts'}">Посты группы</div>
        <div v-on:click="type = 'cart'" :class="{'active': type === 'cart'}">Корзина <span v-if="count_cart > 0">{{count_cart}}</span></div>
    </div>

    <cart v-if="type === 'cart'" :infoItem="infoItem" :cart_items="cart_items" :changeCurrentInfoItem="changeCurrentInfoItem" />

    <posts v-if="type === 'posts'" :currentOrder="currentOrder" :infoItem="infoItem" :changeCurrentInfoItem="changeCurrentInfoItem" :changeOrder="changeOrder" />

    <full_info :currentInfoItem="currentInfoItem" :infoItem="infoItem?.items[currentInfoItem]" :cart_items="cart_items" :cart_add="cart_add" />
</template>

<style>
.provider-menu{
    display: flex;
    padding: 10px 0px;
    background: #2274bb;
    color: white;
    margin-bottom: 10px;
}
.provider-menu > div{
    background: #378ede;
    margin: 0px 10px;
    padding: 5px 20px;
    border-radius: 3px;
}
.provider-menu > div:hover{
    background: #4ca6f8;
    cursor: pointer;
}
.provider-menu > div.active{
    background: #fff;
    color: #2274bb;
}
.provider-menu span{
    position: relative;
    background: red;
    color: white;
    padding: 2px 6px;
    font-size: 13px;
    border-radius: 100%;
    font-weight: bold;
}

</style>
