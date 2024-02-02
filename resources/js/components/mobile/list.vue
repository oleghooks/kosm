<script>
import useEventsBus from "@/EventBus.js";
import Groups from "@/components/mobile/list/groups.vue";
import {post} from "@/post.js";
const {emit}=useEventsBus();
export default {
    components: {Groups},
    props: ['cart_items'],
    watch: {
        cart_items: {
            handler(newCartItems, oldCartItems){
                this.providers_count_carts = {};
                newCartItems.forEach((item) => {
                    if (this.providers_count_carts[item.provide_id])
                        this.providers_count_carts[item.provide_id]++;
                    else
                        this.providers_count_carts[item.provide_id] = 1;
                });

            },
            deep: true
        }
    },
    data(){
        return{
            providers: [
            ],
            currentItem: false,
            providers_count_carts: {

            },
            page: 0,
        }
    },
    methods: {
        info: function(id){
            emit('info', id);
        },
        getList: async function(id){
            if(id) {
                this.currentItem = id;
                emit('info', id);
            }
            let url = "/providers.list";
            let response = await fetch(url);
            response = await response.json();
            this.providers = response.response;
        },
        cancel: function(){
            this.page = 0;
            this.getList();
        },
        favorite: async function(id){
            await post('providers.favorite', {id: id});
            await this.getList();
        }
    },
    mounted() {
        this.getList();
    }
}
</script>

<template>
    <groups v-if="page === 1" :cancel="cancel"></groups>
    <div v-if="page === 0">
        <div style="text-align: right;"><button class="button button-blue" v-on:click="page = 1">Добавить поставщиков</button></div>
        <div v-for="(item, index) in providers" class="list_item" :class="{'list_item_active': currentItem === item.id}">
            <div class="favorite" :class="{'favorite_active': item.favorites > 0}" v-on:click="favorite(item.id)">
            </div>
            <div class="icon" v-on:click="info(item.id); currentItem = item.id;">

                <span class="cart_count" v-if="providers_count_carts[item.id]">{{providers_count_carts[item.id]}}</span>
                <img :src="item.icon"></div>
            <div class="info" v-on:click="info(item.id); currentItem = item.id;">{{item.name}}</div>
        </div>
    </div>
</template>

<style>
.favorite{
    width: 32px;
    height: 32px;
    background-image: url("/img/icons/favorites.png");
    background-repeat: no-repeat;
    background-size: 100% 100%;
    margin: 10px 10px 0px 0px;
}
.favorite_active{

    background-image: url("/img/icons/favorites-active.png");
}
.page_1{
    display: none;
}
.active{
    display: block;
}
.cart_count{
    position: absolute;
    background: red;
    color: white;
    font-weight: bold;
    padding: 3px 7px;
    border-radius: 19px;
}

.list_item{
    display: flex;
    padding: 10px;
    border-bottom: 1px solid #bbb;
}
.list_item_active{
    background: #3897f9d1;
    color: white;
}
.list_item .info{
    margin-left: 12px;
}
.list_item .icon img{
    width: 50px;
    height: 50px;
    border-radius: 100%;
}
</style>
