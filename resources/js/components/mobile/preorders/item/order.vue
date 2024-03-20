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
        }
    }
}
</script>

<template>

    <div v-on:click="menu" style="text-align: center;">{{order.name}} <b>{{order.count}} шт.</b></div>
    <div v-if="is_menu"><button class="button" v-on:click="delete_order">Удалить</button></div>
</template>

<style scoped>
button{
    width: 100%;
    margin: 5px 0px 5px 0px;
    background: #c92a2a;
}
</style>
