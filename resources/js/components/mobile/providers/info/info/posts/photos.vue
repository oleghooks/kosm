<script>
import useEventsBus from "@/EventBus.js";
import {post} from "@/post.js";
const {emit}=useEventsBus();
export default {
    props: ['item', 'cart_items', 'favorites'],
    data(){
        return{
            timer: null,
            indexPhoto: 0,
            isCart: false,
            input: {
                price: "",
                count: "",
            },
            chat_bot: {
                text: "",
                status: 0,
            }
        }
    },
    methods: {
        clickPhoto: function(item){
            if(!this.timer)
            {
                this.timer = setTimeout(() => {
                    // simple click
                    this.timer = null;
                    if(this.isCart === true)
                        this.isCart = false;
                    else{
                        if((item.attachments.length - 1) === this.indexPhoto)
                            this.changePhoto(0);
                        else
                            this.changePhoto(this.indexPhoto + 1);
                    }
                }, 300);//tolerance in ms
            }else{
                clearTimeout(this.timer);
                this.timer = null;
                this.isCart = true;
                // double click
            }
        },
        changePhoto: function (index){
            this.indexPhoto = index;
            this.input.price = "";
            this.input.count = "";
        },
        cart_add: function (){
            emit('cart_add', {
                item_id: this.item.id,
                attach_index: this.indexPhoto,
                count: this.input.count,
                price: this.input.price
            });
        },
        cart_delete: function(){
            emit('cart_delete',
                this.cart_items.findIndex(this.is_cart)
            );
        },
        is_cart: function(element){
            return (element.item_id === this.item.id && element.attach_index === this.indexPhoto)
        },
        is_favorite: function(element){
            return (element.id === this.item.id && element.attach_index === this.indexPhoto)
        },
        favorite_add: function(){
            let favorite = this.item;
            favorite.attach_index = this.indexPhoto;
            emit('favorite_add', favorite);
        },
        favorite_remove: function(){
            emit('favorite_remove',
                this.favorites.findIndex(this.is_favorite)
            );
        },
        favorite: function (){
            if(this.favorites.find(this.is_favorite))
                this.favorite_remove();
            else
                this.favorite_add();
        },
        chat_send: async function(){
            this.chat_bot.status = 1;
            await post('/chatbot.send', {
                image_url: this.item.attachments[this.indexPhoto]?.photo?.sizes.at(-1)?.url,
                text: this.chat_bot.text,
                attach_index: this.indexPhoto
            });
            this.chat_bot.status = 2;
        }
    },
    mounted() {
        if(this.item.attach_index){
            this.indexPhoto = this.item.attach_index
        }
    }
}
</script>

<template>
    <div :class="{'cart_active': isCart}">
        <div class="cart">
            <div v-if="!cart_items.find(is_cart)">
                <div>
                    <input type="number" v-model="input.count"><label>шт.</label>
                    <input type="number" v-model="input.price"><label>цена</label>
                </div>
                <div>
                    <button class="button button-blue" v-on:click="cart_add">Добавить</button>
                    <button class="button button-grey" v-on:click="isCart = false;">Отменить</button>
                </div>
            </div>
            <div v-if="cart_items.find(is_cart)">
                <p>Товар в корзине: {{cart_items.find(is_cart)?.count}} шт. по {{cart_items.find(is_cart)?.price}} руб</p>
                <p><button class="button button-grey" v-on:click="cart_delete">Убрать из корзины</button></p>
            </div>
        </div>
        <img v-if="item.attachments[indexPhoto]?.photo" style="max-width: 100%; width: 100%;" :src="item.attachments[indexPhoto]?.photo?.sizes.at(-1)?.url" v-on:click="clickPhoto(item)">
        <div v-if="item.attachments[indexPhoto]?.photo" :class="{'favorite_active': favorites.find(is_favorite)}" v-on:click="favorite" style="float: right;" class="favorite"></div>
    </div>
    <div style="display: flex" class="photos">
        <div v-for="(attach, index) in item.attachments" :class="{'photo-active': indexPhoto === index}" :style="'background-image: url('+attach.photo?.sizes[0]?.url+')'" v-on:click="changePhoto(index)">
            <span v-if="cart_items.find(item_full => (item_full.item_id === item.id && item_full.attach_index === index))">{{cart_items.find(item_full => (item_full.item_id === item.id && item_full.attach_index === index))?.count}}</span>
        </div>
    </div>
    <div class="chat-bot">
        <div v-if="chat_bot.status === 0">
            <input type="text" v-model="chat_bot.text" placeholder="Цена и описание"><button v-on:click="chat_send" class="button button-blue">Отправить в чат</button>
        </div>
        <div v-if="chat_bot.status === 1">Отправляем..</div>
        <div v-if="chat_bot.status === 2">Отправлено</div>
    </div>
</template>

<style scoped>
.chat-bot{
    text-align: center;
}
.chat-bot input{
    width: 150px;
    padding: 5px;
    border: 1px solid #bbb;
    border-radius: 5px;
}
.cart_active .cart{
    display: block;
}
.cart{
    width: 300px;
    /* position: relative; */
    height: 0px;
    margin: 0 auto;
    background: #fff;
    display: none;
}
.cart > div{
    width: 300px;
    height: 100px;
    background: #ffffffeb;
    position: relative;
    margin-top: 0;
    top: 20px;
    border-radius: 5px;
    text-align: center;
    padding-top: 20px;
}
.cart input{
    padding: 5px;
    border: 1px solid #2559a7;
    outline: none;
    border-radius: 5px 0px 0px 5px;
    border-right: 0px;
    width: 50px;
    height: 29px;
    margin-left: 5px;
}
.cart label{
    background: #2f90b5;
    color: white;
    padding: 4px;
    border-radius: 0px 5px 5px 0px;
    border: 1px solid #2559a7;
    border-left: 0px;
    margin-right: 5px;
}
.photos{
    padding: 10px;
    max-height: 80px;
}
.photos span{

    top: 5px;
    position: relative;
    background: red;
    color: white;
    font-weight: bold;
    padding: 4px;
    border-radius: 4px;
}
.photos > div{
    border: 3px solid #fff;
    border-radius: 3px;
    cursor: pointer;
    width: 75px;
    height: 75px;
    background-repeat: no-repeat;
    background-size: 100% 100%;
}
.photos > div.photo-active{
    border: 3px solid #bbb;
}
</style>
