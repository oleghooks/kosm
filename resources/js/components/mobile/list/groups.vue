<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
import {post} from "@/post.js";

export default {
    props: [
        'cancel'
    ],
    data(){
        return {
            groups_vk: [],
            search_text: "",
            search_result: false,
            checked_groups: []
        }
    },
    methods: {

        search: async function(){
            let url = "/providers.group.info?type=vk&id="+this.search_text;
            let response = await fetch(url);
            response = await response.json();
            this.search_result = response;
            console.log(response);
        },
        add: async function(id){
            emit('is_load_show', 1);
            this.search_text = "";
            this.search_result = false;
            let url = "/providers.group.add?type=vk&id="+id;
            let response = await fetch(url);
            response = await response.json();
            emit('is_load_hidden', 1);
            this.cancel();
        },
        list: async function(){
            let url = "/providers.group.vk_list";
            let response = await fetch(url);
            response = await response.json();
            this.groups_vk = response;
        },
        add_list: async function(){
            emit('is_load_show', 1);
            const request = await post('/providers.group.add.list', {groups: this.checked_groups});
            emit('is_load_hidden', 1);
            this.cancel();
        }
    },
    mounted() {
        this.list();
    }
}
</script>

<template>
    <div class="header-list-groups">
        <button style="float: right" v-if="checked_groups.length > 0" v-on:click="add_list" class="button button-blue">Добавить {{checked_groups.length}} поставщиков</button>
        <button class="button button-grey" v-on:click="cancel">Отменить</button>
    </div>
    <div style="height: 55px;"></div>
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
    <div v-for="(item, index) in groups_vk" class="list_item">
        <div><input type="checkbox" v-model="checked_groups" :value="item.id"> </div>
        <div class="icon">
            <img :src="item.photo_50">
        </div>
        <div class="info">{{item.name}}</div>
    </div>
</template>

<style scoped>
.header-list-groups{
    height: 35px;
    padding: 10px;
    width: calc(100% - 20px);
    position: fixed;
    background: #fff;
    border-bottom: 1px solid #bbb;
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
input[type=checkbox]{
    width: 30px;
    height: 30px;
    border-radius: 30px;
    margin: 9px 13px 5px 0px;
}
</style>
