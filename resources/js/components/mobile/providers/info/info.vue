<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

import ProvideInfo from "@/components/mobile/providers/info/info/provide-info.vue";
import Posts from "@/components/mobile/providers/info/info/posts.vue";
import Cart from "@/components/mobile/providers/info/info/cart.vue";
import {watch} from "vue";
import {post} from "@/post.js";
export default {
    props: ['provider_id', 'back'],

    components: {Cart, Posts, ProvideInfo},
    watch: {
        cart_items: {
            async handler(newCartItems){
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
    data(){
        return {
            infoItem: {
              items: {},
            },
            infoProvider: {

            },
            info: {
                items: [],
                provider: {},
                order: 'popular',
                select_item: 0,
                page: 0,
                id: this.provider_id,
                allSumm: 0,
            },
            currentOrder: 'popular',
            currentId: 0,
            cart_items: [],
            favorites: [],
            page: 0
        }
    },
    methods:{
        calc: function(){
            if(this.info.provider.id > 0) {
                this.info.allSumm = 0;
                this.cart_items.forEach(item => {
                    if (item.provider_id === this.info.provider.id)
                        this.info.allSumm = this.info.allSumm + (item.price * item.count);
                });
            }
        },
        info_provider: async function (id, is_add_page = false){
            emit('is_load_show', 1);
            if(is_add_page === true) {
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
            emit('is_load_hidden', 1);
            this.calc();
        },
        changeOrder: async function (order){
            this.info.order = order;
            await this.info_provider(this.provider_id);
        },
        cart_list: async function(){
            let response = await fetch('/cart.info?provider_id='+this.info.id);
            this.cart_items = await response.json();
        },
        favorites_list: async function(){
            let response = await fetch('/user.favorites');
            this.favorites = await response.json();
        },
        back_cart: async function(){
            this.page = 0;
            await this.cart_list();
        }
    },
    mounted() {
        this.info_provider(this.provider_id);
        this.cart_list();
        this.favorites_list();
        const { bus } = useEventsBus();
        watch(()=>bus.value.get('cart_add'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            post('/cart.add', {
                item_id: item.item_id,
                attach_index: item.attach_index,
                count: item.count,
                price: item.price,
                provider_id: this.info.provider.id
            });
            this.cart_items.push({
                item_id: item.item_id,
                attach_index: item.attach_index,
                count: item.count,
                price: item.price,
                provider_id: this.info.provider.id
            });
        })
        watch(()=>bus.value.get('cart_delete'), (val) => {
            // destruct the parameters
            let [item] = val ?? [];
            post('cart.delete', item);
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
    }
}
</script>

<template>
    <div v-if="page === 0">
        <div class="back" v-on:click="back"><</div>

        <div class="cart_icon" v-on:click="page = 1"><b>{{this.info.allSumm}} руб</b></div>
        <provide-info :infoProvider="info.provider"/>

        <div style="display: flex; margin: 10px 0px;" class="orders">
            <div :class="{'order_active': info.order === 'post_date'}" v-on:click="changeOrder('post_date')">По дате</div>
            <div :class="{'order_active': info.order === 'popular'}" v-on:click="changeOrder('popular')">По популярности</div>
        </div>

        <posts :cart_items="cart_items"  :favorites="favorites" :currentOrder="info.order" :items="info.items" />

        <div class="posts_exe" v-on:click="info_provider(info.id, true)">Еще записи</div>
    </div>
    <cart v-if="page === 1" :provider_id="info.id" :back="back_cart"/>
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
