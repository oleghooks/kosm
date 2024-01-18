<script>
import list from "./left-side/list.vue";
import info from "./right-side/info.vue";
import {watch} from "vue";
import useEventsBus from "@/EventBus.js";

export default {

    data(){
        return{
            cart_items: []

        }
    },
    components: {
        'list': list,
        'info': info,
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
        }
    },
    mounted() {
        const { bus } = useEventsBus()

        watch(()=>bus.value.get('cart_add'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            this.cart_add(item.item_id, item.attach_index, item.count, item.price);
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
            <info :cart_items="cart_items" :cart_add_main="cart_add"></info>
        </div>
    </div>
</template>

<style>
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
</style>
