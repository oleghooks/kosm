<script>
import {post} from "@/post.js";

export default{
    props: ['order', 'list'],
    data(){
        return{
            is_menu: false,
        }
    },
    methods: {
        menu: function (){
            if(this.is_menu === true)
                this.is_menu = false;
            else
                this.is_menu = true
        },
        delete_order: async function(){
            this.is_menu = false;
            await post('/preorders.delete', {preorder_id: this.order.id});
            await this.list();
        },
        edit: async function(){
            this.is_menu = false;
            await post('/preorders.edit', {preorder_id: this.order.id, name: this.order.name, count: this.order.count});
            await this.list();

        }
    }
}
</script>

<template>

    <div v-on:click="menu" v-if="is_menu === false" style="text-align: center;">{{order.name}} <b>{{order.count}} шт.</b></div>
    <div v-if="is_menu">
        <input type="text" v-model="order.name" placeholder="Имя, фамилия">
        <input type="number" v-model="order.count" placeholder="Количество">
        <button class="button button-blue" v-on:click="edit">Сохранить</button>
        <button class="button button-red" v-on:click="delete_order">Удалить</button>

    </div>
</template>

<style scoped>
.button{
    width: 100%;
    margin: 0px;
    margin-bottom: 5px;
}
.button-red{
    width: 100%;
    margin: 5px 0px 5px 0px;
    background: #c92a2a;
}
input[type=text], input[type=number]{
    border: 1px solid #bbb;
    border-radius: 5px;
    width: 100%;
    margin: 0px 0px 5px 0px;
    padding: 5px;
    text-align: center;
}
</style>
