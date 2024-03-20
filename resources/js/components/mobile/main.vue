<script>
import {watch} from "vue";
import useEventsBus from "@/EventBus.js";
import FooterMain from "@/components/mobile/footer-main.vue";
import Providers from "@/components/mobile/providers/main.vue";
import Profile from "@/components/mobile/profile/main.vue";
import Preorders from "@/components/mobile/preorders/main.vue";

import {post} from "@/post.js";
import Orders from "@/components/mobile/orders/main.vue";
import Store from "@/components/mobile/store/main.vue";
export default {
    components: {Store, Orders, Providers, FooterMain, Profile, Preorders},
    data(){
        return{
            currentPage: 1,
            isLoad: false,
            favorites: [],
        }
    },
    watch: {
        favorites:{
            async handler(newFavorites){
                await post('/user.favorites.update', {items: newFavorites});
            },
            deep: true
        },
    },
    methods: {
        changePage: function (index){
            this.currentPage = index;
        },
        make_cart: async function(id){
            this.isLoad = true;
            let response = await post('/cart.make', {id: id});
            response = await response.json();
            emit('orders.list.load', 1);
            emit('orders.info', response.id);
            this.currentPage = 6;
            this.isLoad = false;
        }
    },
    mounted() {
        const { bus } = useEventsBus();

        watch(()=>bus.value.get('is_load_show'), (val) => {
            this.isLoad = true;
        })
        watch(()=>bus.value.get('is_load_hidden'), (val) => {
            this.isLoad = false;
        })
        watch(()=>bus.value.get('select_item'), (val) => {
            let [item] = val ?? [];
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
    <div class="main">
        <div :class="{'active': (currentPage === 1 && !isLoad)}"><providers></providers></div>
         <div :class="{'active': (currentPage === 6 && !isLoad)}"><orders /></div>
        <div :class="{'active': (currentPage === 7 && !isLoad)}"><store /></div>
        <div :class="{'active': (currentPage === 8 && !isLoad)}"><profile /></div>
        <div :class="{'active': (currentPage === 9 && !isLoad)}"><preorders /></div>
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
