<script>
import {post} from "@/post.js";
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

export default{
    props: ['info', 'back_store_main'],
    data(){
        return{

        }
    },
    methods: {
        is_check: function(element){
            return (element.is_check === true)
        },
        add: async function(){
            let items = [];
            this.info.items?.filter(this.is_check).forEach((item) => {
                items.push({
                    item_id: item.item_id,
                    attach_index: item.attach_index,
                    name: item.name,
                    text: item.info.text,
                    price_in: item.price,
                    price_out: item.price_out,
                    photos: item.info.attachments,
                    provider_id: item.provide_id,
                    count: item.count
                });
            });
            emit('is_load_show', 1);
            await post('/store.add', {items: items});
            emit('is_load_hidden', 1);
            this.back_store_main();
        }
    }
}
</script>

<template>
    <div style="height: 35px;     padding: 5px 10px;    border-bottom: 1px solid rgb(187, 187, 187);">
        <button v-if="info.items?.filter(is_check)?.length > 0" class="button-blue button" style="float: right;" v-on:click="add">Добавить товары</button>
        <div style="padding-top: 7px; padding-left: 60px;" v-if="info.items?.filter(is_check)?.length > 0"><b>{{info.items?.filter(is_check)?.length}}</b> позиций выбрано</div>
        <div style="padding-top: 7px; padding-left: 60px;" v-if="info.items?.filter(is_check)?.length === 0">Выберите товары</div>
    </div>
    <div style="height: calc(100% - 45px); overflow-y: scroll">
        <div v-for="(item, index) in info.items" style="
            border-bottom: 1px solid #bbb; padding: 5px;">
            <div  class="implode_list">
                <div style="width: 20%;">
                    <img :src="item.info?.attachments[item.attach_index]?.photo?.sizes[2]?.url">
                    <div style="text-align: center;">
                        <input type="checkbox" v-model="item.is_check">
                    </div>
                </div>
                <div style="width: 80%;">
                    <input type="text" v-model="item.name" placeholder="Наименование товара">
                    <textarea v-model="item.info.text"></textarea>
                </div>
            </div>
            <div class="implode_list_options">
                <div class="option">
                    <input type="number" v-model="item.count"> по
                    <input type="number" v-model="item.price"> руб.
                </div>
                <div class="option">
                    <span>Розница  </span>
                    <input type="number" v-model="item.price_out"> руб.
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input[type=checkbox]{
    width: 30px;
    height: 30px;
    border-radius: 30px;
    margin: 9px 13px 5px 0px;
}
.implode_list{
    display: flex;
}
.implode_list > div > img{
    width: 100%;
    border-radius: 5px;
}
.implode_list > div{
    padding: 5px
}
input[type=text]{
    width: 100%;
    padding: 5px;
    border: 0px;
    border-bottom: 1px solid #bbb;
    font-family: Yasans;
}
textarea{
    padding: 5px;
    font-family: Yasans;
    border: 0px;
    border-bottom: 1px solid #bbb;
    width: 100%;
    height: 200px;
}
.implode_list_options{
    display: flex;
    justify-content: flex-end;
    width: 100%;
}
.implode_list_options .option{
    background: #2274bb;
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 13px;
    margin: 3px;
}
.implode_list_options .option input[type=text], .implode_list_options .option input[type=number]{
    width: 35px;
    padding: 3px;
    background: #175081;
    border: 0px;
    color: white;
    font-size: 13px;
    font-weight: bold;
    border-radius: 3px;
    text-align: center;
}
</style>
