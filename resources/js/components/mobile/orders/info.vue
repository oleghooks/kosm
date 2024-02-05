<script>
import ProvideInfo from "@/components/right-side/info/provide-info.vue";

export default {
    components: {ProvideInfo},
    props: ['info'],
    data(){

    },
    methods: {

    },
    mounted() {

    }
}
</script>

<template>

    <provide-info v-if="info.provider_info" :infoProvider="info.provider_info" />
    <div class="cart">
        <div  class="info-item" v-for="(item, index) in info.items">

            <div>
                <img :src="item.info?.attachments[item.attach_index]?.photo?.sizes[2]?.url">
                <div class="price">
                    <b>{{item.count}} шт</b>
                    по
                    <b>{{item.price}} руб.</b>
                </div>
            </div>

        </div>
    </div>
    <div class="download" style="text-align: center; padding: 20px;">
        <a :href="'/orders.make.photos?id='+info.id">Скачать фото</a>
    </div>
</template>

<style scoped>
.download a{
    background: #2b5f92;
    padding: 5px;
    text-decoration: none;
    color: white;
    display: block;
    border-radius: 5px;
}
.cart{
    display: flex;
    flex-wrap: wrap;
}
.price input[type=text]{
    width: 30px;
    text-align: center;
    border: 0px;
    padding: 3px;
    background: #50abf9;
    color: white;
    outline: none;
}
.info-item{
    margin: 5px;
    border: 1px solid #bbb;
    text-align: center;
    padding: 10px;
    width: calc(50% - 32px);
}
.info-item .price{
    background: #2274bb;
    color: white;
    padding: 5px;
    border-radius: 5px;
}
.info-item:hover{
    border: 1px solid #626262;
    cursor: pointer;
}
.info-item img{
    margin-bottom: 10px;
    border-radius: 7px;
    max-height: 157px;
}
.all_summ{
    padding: 10px;
    font-size: 15px;
    text-align: center;
}
</style>
