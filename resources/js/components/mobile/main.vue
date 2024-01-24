<script>
import List from "@/components/mobile/list.vue";
import {watch} from "vue";
import useEventsBus from "@/EventBus.js";
import Info from "@/components/mobile/info.vue";

export default {
    components: {Info, List},
    data(){
        return{
            currentPage: 1,
            isLoad: false,
            cart_items: []
        }
    },
    watch: {
        cart_items: {
            async handler(newCartItems){
                let response = await fetch('/api/cart.update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify({cart: newCartItems})
                });
            },
            deep: true
        }
    },
    methods: {
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
           let response = await fetch('/api/cart.list');
           this.cart_items = await response.json();
        }
    },
    mounted() {
        this.cart_list();
        const { bus } = useEventsBus();
        watch(()=>bus.value.get('cart_add'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            this.cart_add(item.item_id, item.attach_index, item.count, item.price);
        })
        watch(()=>bus.value.get('is_load_show'), (val) => {
            this.isLoad = true;
        })
        watch(()=>bus.value.get('is_load_hidden'), (val) => {
            this.isLoad = false;
        })
        watch(()=>bus.value.get('info'), (val) => {
            this.currentPage = 2;
        })
        watch(()=>bus.value.get('page_home'), (val) => {
            this.currentPage = 1;
        })
    }
}
</script>

<template>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <div v-if="currentPage === 2" v-on:click="currentPage = 1" class="back"><</div>
    <div class="main">
        <div :class="{'active': (currentPage === 1 && !isLoad)}"><list :cart_items="cart_items"></list></div>
        <div :class="{'active': (currentPage === 2 && !isLoad)}"><info  :cart_items="cart_items" :cart_add_main="cart_add" /></div>
        <div>3</div>
        <div>4</div>
        <div>5</div>
        <div class="load" :class="{'active': isLoad}"></div>
    </div>
    <div class="footer">
        <div v-on:click="currentPage = 1">1</div>
        <div v-on:click="currentPage = 2">2</div>
        <div v-on:click="isLoad = true">3</div>
        <div v-on:click="isLoad = false">4</div>
        <div>5</div>
    </div>
</template>

<style>
@font-face {
    font-family: "Yasans";
    src: url("/fonts/YandexSansText-Regular.ttf") format("truetype");
    font-style: normal;
    font-weight: normal;
}
.back{
    position: fixed;
    padding: 9px;
    margin: 10px;
    width: 30px;
    text-align: center;
    background: #fff;
    border-radius: 50%;
    font-size: 25px;
    font-weight: bold;
    color: #237bc7;
    text-shadow: 0 0 black;
    box-shadow: 0 0 15px 4px #0000003d;
    z-index: 100;
    opacity: 0.9;

}
body{
    padding: 0px;
    margin: 0px;
    font-size: 15px;
    font-family: Yasans;
}
.main{
    display: block;
    height: calc(100% - 51px);
    width: 100%;
    overflow-y: auto;
    overflow-x: hidden;
}
.main > div.active{
    display: block;
}
.main > div{
    display: none;
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
.footer{
    border-top: 1px solid #2274bb;
    background: #b4b4b4;
    width: 100%;
    height: 50px;
    display: flex;
}
.footer > div{

    margin: 0px 10px;
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
