<script>
import {post} from "@/post.js";
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

export default {
    props: ['info', 'back'],
    data(){
        return{
            sale: {
                count: 1,
                price_out: this.info.price_out,
                name: "",
                phone: "",
                note: "",
                source: "vk"
            }
        }
    },
    methods: {
        add: async function(){

            emit('is_load_show', 1);
            await post('/sales.add', {
                id: this.info.id,
                count: this.sale.count,
                price_out: this.sale.price_out,
                name: this.sale.name,
                phone: this.sale.phone,
                note: this.sale.note,
                source: this.sale.source
            });
            emit('is_load_hidden', 1);
            this.back();
        }
    }
}
</script>

<template>
    <div class="header_page_is_back">Новая продажа</div>
    <div class="store_item">
        <div class="store_item_photo">
            <img :src="info.photos[info.attach_index].photo.sizes.at(-3).url">
        </div>
        <div class="store_item_desc">
            <p>{{info.name}}</p>
            <p>Склад: <b>{{info.count}} шт</b></p>
            <p>Цена продажи: <b>{{info.price_out}} руб.</b></p>
            <p>Цена закупки: <b>{{parseInt(info.price_in * 1.17)}} руб.</b>  (тк + 17%)</p>
        </div>
    </div>
    <div class="sale_info">
        <p>
            <select v-model="sale.source">
                <option value="vk">ВК</option>
                <option value="telegram">Телеграм</option>
                <option value="saraphan">Сарафанка</option>
                <option value="main">Себе</option>
                <option value="other">Другое</option>
            </select>
        </p>
        <button v-on:click="sale.count++">+</button>
        <input type="number" v-model="sale.count">
        <button v-on:click="sale.count--">-</button>
        <span>Цена продажи</span>
        <input type="number" v-model="sale.price_out"><b>руб.</b>
    </div>
    <div class="client_info">
        <input type="text" placeholder="Имя" v-model="sale.name"><input type="number" v-model="sale.phone" placeholder="Телефон">
        <textarea placeholder="Заметка о клиенте" v-model="sale.note"></textarea>
    </div>
    <div style="padding: 10px; text-align: center">
        <button class="button button-blue" v-on:click="add">Сохранить</button>
    </div>
</template>

<style scoped>
.sale_info{
    padding: 10px;
    text-align: center;
}
.sale_info button{
    padding: 5px;
    width: 30px;
    text-align: center;
}
.sale_info span{
    margin-left: 10px;
}
.sale_info input[type=number]{
    width: 40px;
    text-align: center;
}
.store_item{
    display: flex;
    padding: 10px;
    border-bottom: 1px solid #bbb;
}
.store_item_photo{
    width: 20%;
    padding-right: 5px;
}
p{
    margin: 0px;
}
.store_item_photo img{
    width: 100%;
    border-radius: 3px;
}
.store_item_desc{
    width: 80%;
}
input{
    padding: 7px;
    border: 1px solid #bbb;
    border-radius: 5px;
    margin: 3px;
    width: calc(100% - 6px);
}
.client_info{
    padding: 10px;
}
.client_info input{
    width: calc(50% - 6px);
}
.client_info textarea{
    width: 100%;
    padding: 8px;
    height: 85px;
    font-family: 'Yasans';
    border: 1px solid #bbb;
    border-radius: 4px;
}
</style>
