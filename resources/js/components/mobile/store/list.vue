<script>
export default {
    props: ['items', 'info'],
    data(){
        return {

        }
    },

    methods: {

    }
}
</script>

<template>
    <div class="store_item" v-for="(item,index) in items" v-on:click="info(item.id)">
        <div class="store_item_photo">
            <img :src="item.photos[item.attach_index].photo.sizes.at(-3).url">
        </div>
        <div class="store_item_desc">
            <p>{{item.name}}</p>
            <p>Склад: <b>{{item.count}} шт</b></p>
            <p>Цена продажи: <b>{{item.price_out}} руб.</b></p>
            <p>Цена закупки: <b>{{parseInt(item.price_in * 1.17)}} руб.</b>  (тк + 17%)</p>
        </div>
    </div>
</template>

<style scoped>

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
</style>
