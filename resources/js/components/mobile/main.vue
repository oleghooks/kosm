<script>
import {watch} from "vue";
import useEventsBus from "@/EventBus.js";
import FooterMain from "@/components/mobile/footer-main.vue";
import Providers from "@/components/mobile/providers/main.vue";

import {post} from "@/post.js";
import Orders from "@/components/mobile/orders.vue";
import Store from "@/components/mobile/store.vue";
export default {
    components: {Store, Orders, Providers, FooterMain},
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
        const { bus } = useEventsBus();

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
    <div class="main">
        <div :class="{'active': (currentPage === 1 && !isLoad)}"><providers></providers></div>
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
