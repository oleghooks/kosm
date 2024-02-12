<script>

import {post} from "@/post.js";
import Sale from "@/components/mobile/store/info/sale.vue";
export default {
    components: {Sale},
    props: ['info', 'back_store_main'],
    data(){
        return{
            store: {},
            isEdit: 0,
            currentPage: 0,
        }
    },
    methods: {
        edit: async function(){
            this.isEdit = 0;
            await post('/store.edit', {item: {
                    id: this.info.id,
                    text: this.info.text,
                    name: this.info.name,
                    price_out: this.info.price_out
                }})
        }
    },
}
</script>

<template>
    <div v-if="currentPage === 0">
        <div v-on:click="back_store_main" class="back"><</div>
        <div>
            <img class="main_photo" :src="info.photos[info.attach_index].photo.sizes.at(-1).url">
        </div>
        <div style="text-align: center">
            <button class="button button-grey">{{info.count}} шт</button>
            <button class="button button-grey"><span v-if="isEdit === 0">{{info.price_out}}</span><input v-if="isEdit === 1" type="number" v-model="info.price_out"> руб</button>
            <button class="button button-blue" v-on:click="currentPage = 1">Продажа</button>
            <button class="button button-blue" v-if="isEdit === 0" v-on:click="this.isEdit = 1;">Редактировать</button>
            <button class="button button-blue" v-if="isEdit === 1" v-on:click="edit">Сохранить</button>
        </div>
        <div v-if="isEdit === 0" style="padding: 10px;">{{info.name}}</div>
        <div v-if="isEdit === 0" style="padding: 10px;"  v-html="info.text.replace(/([^>])\n/g, '$1<br/>')"></div>
        <div v-if="isEdit === 1">Название</div>
        <input class="store_info_edit_text" v-if="isEdit === 1" type="text" v-model="info.name">
        <div v-if="isEdit === 1">Описание</div>
        <textarea v-if="isEdit === 1" v-model="info.text"></textarea>
    </div>
    <div v-if="currentPage === 1">
        <div v-on:click="currentPage = 0" class="back"><</div>
        <sale v-if="currentPage === 1" :info="info" :back="back_store_main"/>
    </div>
</template>

<style scoped>
.store_info_edit_text{
    width: calc(100% - 10px);
    border: 0px;
    font-family: Yasans;
    padding: 10px;
    margin: 5px;
    border: 1px solid #bbb;
}
textarea{
    width: 100%;
    height: 300px;
    font-family: Yasans;
}
button input[type=number]{
    background: #a3a3a3;
    border-radius: 3px;
    border: 0px;
    width: 30px;
}
.main_photo{
    width: 100%;
}
</style>
