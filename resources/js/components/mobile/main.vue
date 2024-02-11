<script>
import List from "@/components/mobile/list.vue";
import {watch} from "vue";
import useEventsBus from "@/EventBus.js";
import Info from "@/components/mobile/info.vue";
import Cart from "@/components/mobile/info/cart.vue";
import Post from "@/components/mobile/info/posts/post.vue";
import FooterMain from "@/components/mobile/footer-main.vue";

import {post} from "@/post.js";
import Posts from "@/components/right-side/info/posts.vue";
import Orders from "@/components/mobile/orders.vue";
import Store from "@/components/mobile/store.vue";
export default {
    components: {Store, Orders, Posts, FooterMain, Post, Cart, Info, List},
    data(){
        return{
            currentPage: 1,
            isLoad: false,
            cart_items: [],
            favorites: [],
            info: {
                items: [],
                provider: {},
                order: 'popular',
                select_item: 0,
                page: 0,
                id: 0,
                allSumm: 0,
            }
        }
    },
    watch: {
        cart_items: {
            async handler(newCartItems){
                await post('/cart.update', {cart: newCartItems});
                this.calc();
            },
            deep: true
        },
        favorites:{
            async handler(newFavorites){
                await post('/user.favorites.update', {items: newFavorites});
            },
            deep: true
        },
    },
    methods: {
        calc: function(){
            if(this.info.provider.id > 0) {
                this.info.allSumm = 0;
                this.cart_items.forEach(item => {
                    if (item.provide_id === this.info.provider.id)
                        this.info.allSumm = this.info.allSumm + (item.price * item.count);
                });
            }
        },
        cart_add: function(item_id, attach_index, count, price, provide_id){
            this.cart_items.push({
                item_id: item_id,
                attach_index: attach_index,
                count: count,
                price: price,
                provide_id: provide_id
            });
        },
        cart_list: async function(){
           let response = await fetch('/cart.list');
           this.cart_items = await response.json();
        },
        favorites_list: async function(){
            let response = await fetch('/user.favorites');
            this.favorites = await response.json();
        },
        infoProvider: async function(id, changeOrder = false){
            this.currentPage = 2;
            if(this.info.id === id && changeOrder === false) {
                this.info.page++;
            }
            else{
                this.isLoad = true;
                this.info.items = [];
                this.info.provider = {};
            }
            this.info.id = id;
            let url = '/providers.info?id='+id+'&order='+this.info.order+'&p='+this.info.page;
            let response = await fetch(url);
            response = await response.json();
            response.items.forEach((item) => {
                this.info.items.push(item);
            });
            this.info.provider = response.provider;
            this.isLoad = false;
            this.calc();
        },
        changeOrder: async function(type){
            this.info.order = type;
            await this.infoProvider(this.info.provider.id, true);
        },
        changePage: function (index){
            this.currentPage = index;
        },
        make_cart: async function(id){
            this.isLoad = true;
            let response = await post('/cart.make', {id: id})
            this.cart_list();
            this.currentPage = 6;
            this.isLoad = false;
        }
    },
    mounted() {
        this.cart_list();
        this.favorites_list();
        const { bus } = useEventsBus();
        watch(()=>bus.value.get('cart_add'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            this.cart_add(item.item_id, item.attach_index, item.count, item.price, this.info.provider.id);
        })
        watch(()=>bus.value.get('cart_delete'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            this.cart_items.splice(this.cart_items.findIndex(items => items === item), 1);
        })
        watch(()=>bus.value.get('favorite_add'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            this.favorites.push(item);
        })
        watch(()=>bus.value.get('favorite_remove'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            this.favorites.splice(item, 1);
        })
        watch(()=>bus.value.get('is_load_show'), (val) => {
            this.isLoad = true;
        })
        watch(()=>bus.value.get('is_load_hidden'), (val) => {
            this.isLoad = false;
        })
        watch(()=>bus.value.get('info'), (val) => {
            let [item] = val ?? [];
            this.infoProvider(item);
        })
        watch(()=>bus.value.get('change_order'), (val) => {
            let [item] = val ?? [];
            this.changeOrder(item);
        })
        watch(()=>bus.value.get('select_item'), (val) => {
            let [item] = val ?? [];
            this.info.select_item = item;
            this.currentPage = 4;
        })
        watch(()=>bus.value.get('make_cart'), (val) => {
            let [item] = val ?? [];
            this.make_cart(item);
        })
    }
}
</script>

<template>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <div v-if="currentPage > 1 && currentPage < 5" v-on:click="currentPage = currentPage - 1" class="back"><</div>
    <div v-if="currentPage === 2" v-on:click="currentPage = 3" class="cart_icon"><b>{{this.info.allSumm}} руб</b></div>
    <div class="main">
        <div :class="{'active': (currentPage === 1 && !isLoad)}"><list :cart_items="cart_items"></list></div>
        <div :class="{'active': (currentPage === 2 && !isLoad)}"><info  :cart_items="cart_items" :favorites="favorites" :info="info"/></div>
        <div :class="{'active': (currentPage === 3 && !isLoad)}"><cart v-if="currentPage === 3" :cart_items="cart_items" :info="info" /></div>
        <div :class="{'active': (currentPage === 4 && !isLoad)}"><post v-if="info.items.length > 0" :cart_items="cart_items" :favorites="favorites" :item="info.items[info.select_item]" /></div>
        <div :class="{'active': (currentPage === 5 && !isLoad)}"><post v-for="(item, index) in favorites" :item="item" :cart_items="cart_items"  :favorites="favorites" /></div>
        <div :class="{'active': (currentPage === 6 && !isLoad)}"><orders v-if="currentPage === 6" /></div>
        <div :class="{'active': (currentPage === 7 && !isLoad)}"><store v-if="currentPage === 7" /></div>
        <div>5</div>
        <div class="load" :class="{'active': isLoad}"></div>
    </div>
    <footer-main :currentPage="currentPage" :changePage="changePage" ></footer-main>
</template>

<style>
@font-face {
    font-family: "Yasans";
    src: url("/fonts/YandexSansText-Regular.ttf") format("truetype");
    font-style: normal;
    font-weight: normal;
}
.back, .cart_icon{
    position: fixed;
    padding: 6px;
    margin: 10px;
    width: 23px;
    text-align: center;
    background: #fff;
    border-radius: 50%;
    font-size: 18px;
    font-weight: bold;
    color: #237bc7;
    text-shadow: 0 0 black;
    box-shadow: 0 0 15px 4px #0000003d;
    z-index: 100;
    opacity: 0.9;

}
.cart_icon{
    right: 10px;
    width: auto;
}
.cart_icon b{
    font-size: 13px;
}
body{
    padding: 0px;
    margin: 0px;
    font-size: 15px;
    font-family: Yasans;
}
.main{
    display: block;
    height: 100%;
    width: 100%;
    overflow-y: hidden;
    overflow-x: hidden;
}
.main > div.active{
    display: block;
}
.main > div{
    display: none;
    height: calc(100% - 57px);
    width: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    padding-bottom: 100px;
}
.load{
    width: -webkit-fill-available;
    height: -webkit-fill-available;
    background: #fff;
    display: block;
    background-image: url(/img/icons/load.gif);
    background-repeat: no-repeat;
    background-position: 50% 50%;
}


.button{
    padding: 5px 10px;
    margin: 5px;
    border: 0px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
}

.button-blue{
    background: #2274bb;
}
.button-grey{
    background: #c7c7c7;
    color: #1a202c;
}
</style>
