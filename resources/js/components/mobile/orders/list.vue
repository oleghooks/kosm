<script>
export default {
    props: ['info', 'list'],
    data(){
        return {
            status_text: [
                'Создана',
                'Согласована',
                'В доставке',
                'Доставлено'
            ]
        }
    }
}
</script>

<template>
<div class="orders_list" v-for="item in list" v-on:click="info(item.id)">
    <div class="orders_list_header">
        <div style="float: right; line-height: 31px;     position: relative;   top: -8px;">{{item.provider_info.name}} <img :src="item.provider_info.icon" /> </div>
        <div><b>Заказ №{{item.id}}</b></div>
    </div>
    <div style="clear: both;"></div>
    <div class="orders_list_body">
        <img v-for="photo in item.items_photos" :src="photo">
    </div>
    <div class="orders_list_footer">
        <p><b>{{item.count}}</b> товаров на сумму <b>{{item.summ}} руб</b></p>
        <p>
            <span class="orders_list_footer_time">{{item.time}}</span>
            <span class="orders_list_footer_status">{{status_text[item.status]}}</span>
        </p>
    </div>
</div>
</template>

<style scoped>
.orders_list_footer_time{
    float: right;
    display: block;
    color: #bbb;
    font-weight: bold;
    font-size: 11px;
}
.orders_list_footer_status{
    background: #bbb;
    padding: 5px 10px;
    color: #575757;
    font-size: 11px;
    font-weight: bold;
    border-radius: 5px;
}
.orders_list{
    padding: 10px;
    border-bottom: 1px solid #1a202c;
}
.orders_list_header{
}
.orders_list_header img{
    width: 24px;
    height: 24px;
    border-radius: 100%;
    margin-left: 10px;
    position: relative;
    top: 5px;
}
.orders_list_body img{
    width: 24px;
    height: 24px;
    border-radius: 100%;
    margin: 5px;
}
.orders_list_footer p {
    margin: 5px;
}
</style>
