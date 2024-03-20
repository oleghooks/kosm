<script>
import {post} from "@/post.js";
import Order from "@/components/mobile/preorders/item/order.vue";

export default {
    components: {Order},
    props: ['item', 'list'],
    data(){
        return {
            is_add: false,
            input_name: "",
            input_count: 1,
            is_load: false,
        }
    },
    methods: {
        add: async function (){
            this.is_load = true;
            await post('/preorders.add', {
                chat_bot_id: this.item.id,
                name: this.input_name,
                count: this.input_count
            });
            await this.list();
            this.is_load = false;
            this.is_add = false;
            this.input_name = "";
            this.input_count = 1;
        }
    }
}
</script>

<template>
    <div class="item">
        <div class="image"  :style="'background-image: url('+item.item.attachments[item.attach_index]?.photo?.sizes[2].url+');'"></div>
        <p>{{item.text_send}}</p>
        <div class="preorders">
            <order  v-for="order in item.preorders" :order="order" :list="list" />
        </div>
        <button class="button button-blue" v-if="is_add === false && is_load === false" v-on:click="is_add = true">Добавить заказ</button>
        <div v-if="is_add === true && is_load === false">
            <input type="text" v-model="input_name" placeholder="Имя, фамилия">
            <input type="number" v-model="input_count" placeholder="Количество">
            <button class="button button-blue" v-on:click="add">Сохранить</button>
            <button class="button button-grey" v-on:click="is_add = false">Отмена</button>
        </div>
        <div v-if="is_load === true" style="text-align: center">
            Сохраняем...
        </div>
    </div>
</template>

<style scoped>

.item{
    width: calc(46% - 2px);
    margin: 1%;
    border: 1px solid #bbb;
    padding: 1%;
    border-radius: 5px;
}
.item .button{
    width: 100%;
    margin: 0px;
    margin-bottom: 5px;
}
.preorders > div{
    text-align: center;
    margin: 5px 0px 5px 0px;
}
p{
    text-align: center;
    margin: 5px 0px 0px 0px;
}
.image{
    width: 100%;
    height: 200px;
    background-size: auto 100%;
    background-position: 50% 50%;
    background-repeat: no-repeat;
    border-radius: 5px;
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
