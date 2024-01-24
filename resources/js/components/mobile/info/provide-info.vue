<script>
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
export default {
    props: [
        'infoProvider'
    ],
    methods: {
    }
}
</script>

<template>
    <div class="provide_info" v-if="infoProvider.id > 0">
        <div class="provide_info_icon" style=""><img :src="infoProvider.icon"></div>
        <div style="">
            <div class="provide_info_title">{{infoProvider.name}}</div>
            <div class="provide_info_min_summ">Минимальная сумма для заказа: {{infoProvider.min_summ}} руб</div>
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
    width: 50px;
    height: 50px;
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
