<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

import ProvideInfo from "@/components/mobile/info/provide-info.vue";
import Posts from "@/components/mobile/info/posts.vue";
import Cart from "@/components/mobile/info/cart.vue";
export default {
    props: ['cart_items', 'info', 'favorites'],

    components: {Cart, Posts, ProvideInfo},
    data(){
        return {
            infoItem: {
                items: {},
            },
            infoProvider: {

            },
            currentOrder: 'popular',
            currentId: 0,
        }
    },
    methods:{
        info_exe: function (){
            emit('info', this.info.provider.id);
        }
    }
}
</script>

<template>
    <provide-info :infoProvider="info.provider"/>

    <posts :cart_items="cart_items"  :favorites="favorites" :currentOrder="info.order" :items="info.items" />

    <div class="posts_exe" v-on:click="info_exe">Еще записи</div>
    <!--<cart  :infoItem="infoItem" :cart_items="cart_items" :changeCurrentInfoItem="changeCurrentInfoItem" :provide_id="currentId" />
    <full_info :currentInfoItem="currentInfoItem" :infoItem="infoItem?.items[currentInfoItem]" :cart_items="cart_items" />-->
</template>

<style scoped>
.posts_exe{
    padding: 10px;
    text-align: center;
    background: #bbb;
    border-radius: 10px;
    margin: 10px;
}
</style>
