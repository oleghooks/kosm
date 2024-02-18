<script>

import useEventsBus from "@/EventBus.js";
import Post from "@/components/mobile/providers/info/info/posts/post.vue";
const {emit}=useEventsBus();
export default {
    components: {Post},
    props: [
        'currentOrder',
        'items',
        'cart_items',
        'favorites',
    ],
    methods: {
        changeOrder: function(value){
            emit('change_order', value)
        },
        info: function (){

        }
    }
}
</script>

<template>
    <div class="">

        <post v-for="(item, index) in items" :item="item" :cart_items="cart_items"  :favorites="favorites" />
    </div>
</template>

<style>
.posts{

}



</style>
