<script>
export default {
    props: [
        'infoItem',
        'changeCurrentInfoItem',
        'cart_items'
    ],
    data(){
        return {
            allSumm: 0
        }
    },
    watch: {
        cart_items: {
            deep: true,
            handler(newCartItems, oldCartItems) {
                this.calc();
            }
        }
    },
    methods: {
        calc: function(){

            this.allSumm = 0;
            this.cart_items.forEach(item => {
                console.log(item);
                this.allSumm = this.allSumm + (item.price * item.count);
            });
        },
        getIndex: function(id1, id2){
            return id1 === id2;
        }
    },
    mounted() {
        this.calc();
    }
}
</script>

<template>
    <div style="display: flex;">
        <div  class="info-item" v-for="(item, index) in cart_items">

            <div v-on:click="changeCurrentInfoItem(infoItem.items.findIndex(item_full => item_full.id === item.item_id), item.attach_index)">
                <img :src="infoItem.items.find(item_full => item_full.id === item.item_id)?.attachments[item.attach_index]?.photo?.sizes[2]?.url">
                <div class="price">{{item.count}} шт. x {{item.price}} руб.</div>
            </div>

        </div>
    </div>
    <div v-if="allSumm > 0" class="all_summ">Общая сумма: <b>{{allSumm}} руб</b></div>
</template>

<style scoped>
.info-item{
    margin: 5px;
    padding: 10px;
    border: 1px solid #bbb;
    text-align: center;
}
.info-item:hover{
    border: 1px solid #626262;
    cursor: pointer;
}
.info-item img{
    margin-bottom: 10px;
}
.all_summ{
    padding: 10px;
    font-size: 15px;
}
</style>
