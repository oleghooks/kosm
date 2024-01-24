<script>
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
export default {
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
            search_text: "",
            search_result: false,
            providers_count_carts: {

            }
        }
    },
    methods: {
        info: function(id){
            emit('info', id);
        },
        search: async function(){
            let url = "/api/providers.group.info?type=vk&id="+this.search_text;
            let response = await fetch(url);
            response = await response.json();
            this.search_result = response;
            console.log(response);
        },
        add: async function(id){
            this.search_text = "";
            this.search_result = false;
            let url = "/api/providers.group.add?type=vk&id="+id;
            let response = await fetch(url);
            response = await response.json();
            this.getList(response.id);
        },
        getList: async function(id){
            this.currentItem = id;
            emit('info', id);
            let url = "/api/providers.list";
            let response = await fetch(url);
            response = await response.json();
            this.providers = response.response;
        }
    },
    mounted() {
        this.getList();
    }
}
</script>

<template>
    <div>
        <input  class="search_text" type="text" v-model="search_text" placeholder="Введите короткое имя или ID группы"  @keyup.enter="search">
    </div>
    <div v-if="search_result" class="search_result">
        <div class="list_item"  v-on:click="add(search_result?.response[0]?.id);">
            <div class="icon">
                <img :src="search_result?.response[0]?.photo_50"></div>
            <div class="info">{{search_result?.response[0]?.name}}</div>
        </div>
    </div>
    <div v-for="(item, index) in providers" class="list_item" :class="{'list_item_active': currentItem === item.id}" v-on:click="info(item.id); currentItem = item.id;">
        <div class="icon">

            <span class="cart_count" v-if="providers_count_carts[item.id]">{{providers_count_carts[item.id]}}</span>
            <img :src="item.icon"></div>
        <div class="info">{{item.name}}</div>
    </div>
</template>

<style>
.cart_count{
    position: absolute;
    background: red;
    color: white;
    font-weight: bold;
    padding: 3px 7px;
    border-radius: 19px;
}
.search_text{
    width: 100%;
    border: 0px;
    border-bottom: 1px solid #bbb;
    padding: 13px;
    outline: none;
    font-size: 15px;
}
.search_result{
    border-bottom: 1px solid #bbb;
    padding-bottom: 20px;
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
