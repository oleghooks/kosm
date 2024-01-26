<script>
import list from "./left-side/list.vue";
import info from "./right-side/info.vue";
import {watch} from "vue";
import useEventsBus from "@/EventBus.js";
import {post} from "@/post.js";

export default {

    data(){
        return{
            cart_items: [],
            isLoad: false,
        }
    },
    components: {
        'list': list,
        'info': info,
    },
    watch: {
        cart_items: {
            async handler(newCartItems){
                let response = await post('/cart.update', {cart: newCartItems});
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
           let response = await fetch('/cart.list');
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
        watch(()=>bus.value.get('cart_delete'), (val) => {
            let [item] = val ?? [];
            this.cart_items.splice(this.cart_items.findIndex(items => items === item), 1);
        })
    }
}
</script>

<template>

    <div class="main">
        <div class="menu">
            <div icon="home"></div>
        </div>
        <div class="left-side">
            <list :cart_items="cart_items"></list>
        </div>
        <div class="right-side">
            <div class="load-page" :class="{'display-block': isLoad}"></div>
            <info :cart_items="cart_items" :cart_add_main="cart_add"></info>
        </div>
    </div>
</template>

<style>
.load-page{
    position: fixed;
    width: -webkit-fill-available;
    height: -webkit-fill-available;
    background: #fff;
    display: block;
    background-image: url(/img/icons/load.gif);
    background-repeat: no-repeat;
    background-position: 50% 50%;
    display: none;
}
.display-block{
    display: block;
}
body{
    margin: 0px;
    height: 100%;
}
.main{
    display: flex;
    height: 100%;
}


.menu{
    width: 80px;
    background: #4b5563;
}
.menu > div{
    width: 40px;
    height: 40px;
    background-size: 100% 100%;
    margin: 8px;
}
div[icon=home]{
    background-image: url('/img/icons/home_icon.png');
}



.left-side{
    width: 400px;
    height: 100%;
    border-right: 1px solid #bbb;
}
.right-side{
    width: 100%;
    height: 100%;
    overflow-y: scroll;
}
body{
    font-family: Arial;
}
button.button{
    background: #4387c9;
    color: white;
    font-weight: bold;
    border: 0px;
    border-radius: 3px;
    padding: 5px 10px
}
button.button:hover{
    background: #2b5f92;
    cursor: pointer;
}
</style>
