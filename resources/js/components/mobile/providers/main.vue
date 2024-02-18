<script>
import useEventsBus from "@/EventBus.js";
import Groups from "@/components/mobile/providers/list/groups.vue";
import {post} from "@/post.js";
import List from "@/components/mobile/providers/list/list.vue";
import Info from "@/components/mobile/providers/info/info.vue";
const {emit}=useEventsBus();
export default {
    components: {Info, List, Groups},
    props: ['cart_items'],
    data(){
        return{
            providers: [
            ],
            currentItem: false,
            page: 0,
            provider_id: 0,
        }
    },
    methods: {
        info: function(id){
            this.provider_id = id;
            this.page = 2;
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
        <list :providers="providers" :list="getList" :info="info" />
    </div>
    <info v-if="page === 2" :provider_id="provider_id" :back="cancel"></info>
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
