<script>
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
export default {
    data(){
        return{
            providers: [
                {
                    name: 'САДОВОД КОСМЕТИКА',
                    parser_type: 'vk',
                    parser_id: '-122557292',
                    icon: 'https://sun83-2.userapi.com/s/v1/if2/3YGndDcufAIDUzmL2yIqgyOVExh-4iQzrrwbzfUspZ3vYVZUWwFdfV2gmMvbzJBWFPIV3iu5_vOhjMLn2U_8DpZu.jpg?quality=95&crop=117,0,400,400&as=50x50,100x100,200x200,400x400&ava=1&u=kmrXVUl2OQAdG0CO4ntUWr8ZePPatDZNh385tJYCFro&cs=100x100'
                },
                {
                    name: 'Косметика. Оптом. Садовод',
                    parser_type: 'vk',
                    parser_id: '-179378243',
                    icon: 'https://sun83-2.userapi.com/s/v1/ig2/eWBibsmN-S05Jb6alGdIXkCjFTW3PunifP05dl4xfuDRzH0uJ0T8zPR_tx0_EJNy6GE_035RbX0Q_gAGeMC4l7N8.jpg?size=100x100&quality=95&crop=0,352,1920,1920&ava=1'
                }
            ],
            currentItem: false,
            search_text: "",
            search_result: false,
        }
    },
    methods: {
        info: function(id){
            emit('info', id);
        },
        search: async function(){
            let url = "/api/info?type=vk&id="+this.search_text;
            let response = await fetch(url);
            response = await response.json();
            this.search_result = response;
            console.log(response);
        },
        add: async function(id){
            this.search_text = "";
            this.search_result = false;
            let url = "/api/add?type=vk&id="+id;
            let response = await fetch(url);
            response = await response.json();
            this.getList(response.id);
        },
        getList: async function(id){
            this.currentItem = id;
            emit('info', id);
            let url = "/api/list";
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
            <div class="icon"><img :src="search_result?.response[0]?.photo_50"></div>
            <div class="info">{{search_result?.response[0]?.name}}</div>
        </div>
    </div>
    <div v-for="(item, index) in providers" class="list_item" :class="{'list_item_active': currentItem === item.id}" v-on:click="info(item.id); currentItem = item.id;">
        <div class="icon"><img :src="item.icon"></div>
        <div class="info">{{item.name}}</div>
    </div>
</template>

<style>
.search_text{
    width: 100%;
    border: 0px;
    border-bottom: 1px solid #bbb;
    padding: 13px;
    outline: none;
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
.list_item:hover{
    cursor: pointer;
    background: #bbb;
}
.list_item_active{
    background: #3897f9d1;
    color: white;
}
.list_item .info{
    margin-left: 12px;
}
.list_item .icon img{
    width: 40px;
    height: 40px;
    border-radius: 100%;
}
</style>
