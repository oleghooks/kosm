<script>
import Photos from "@/components/mobile/info/posts/photos.vue";
import useEventsBus from "@/EventBus.js";
import Post from "@/components/mobile/info/posts/post.vue";
const {emit}=useEventsBus();
export default {
    components: {Post, Photos},
    props: [
        'currentOrder',
        'items',
        'cart_items',
        'favorites',
    ],
    methods: {
        changeOrder: function(value){
            emit('change_order', value)
        }
    }
}
</script>

<template>
    <div class="posts">
        <div style="display: flex" class="orders" v-if="items.length > 0">
            <div :class="{'order_active': currentOrder === 'post_date'}" v-on:click="changeOrder('post_date')">По дате</div>
            <div :class="{'order_active': currentOrder === 'popular'}" v-on:click="changeOrder('popular')">По популярности</div>
        </div>
        <post v-for="(item, index) in items" :item="item" :cart_items="cart_items"  :favorites="favorites" />
    </div>
</template>

<style>
.posts{

}

.orders > div{
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

.orders > div:hover{
    background: #bbb;
}

.orders > .order_active{
    background: #bbb;
}


</style>
