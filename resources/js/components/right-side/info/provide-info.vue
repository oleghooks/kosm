<script>
export default {
    props: [
        'infoProvider'
    ]
}
</script>

<template>
    <div class="provide_info" v-if="infoProvider.id > 0">
        <div class="provide_info_icon" style="width: 70px;"><img :src="infoProvider.icon"></div>
        <div style="line-height: 31px;width: calc(70% - 100px);">
            <div class="provide_info_title">{{infoProvider.name}}</div>
            <div class="provide_info_min_summ">Минимальная сумма для заказа: {{infoProvider.min_summ}} руб</div>
        </div>
        <div style="width: 30%; display: flex;">
            <div>Последнее обновление : </div>
            <div><button>Обновить посты</button></div>

        </div>
    </div>
</template>

<style>
.provide_info{
    display: flex;
    border-bottom: 1px solid #bbb;
    padding: 10px;
    margin-bottom: 10px;
}
.provide_info_icon{
    margin-right: 15px;
}
.provide_info_icon img{
    width: 64px;
    height: 64px;
    border-radius: 50%;
}
.provide_info_title{
    font-weight: bold;
    color: #5caafa;
}
.provide_info_min_summ{
    color: #bbb;
    font-weight: bold;
}
</style>
