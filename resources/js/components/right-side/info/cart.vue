<script>
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

export default {
    props: [
        'infoItem',
        'changeCurrentInfoItem',
        'cart_items',
        'provide_id'
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
        },
        infoItem: {
            deep: true,
            handler(newCartItems, oldCartItems) {
                this.calc();
            }
        }
    },
    methods: {
        calc: function(){

            this.allSumm = 0;
            this.cart_items?.forEach(item => {
                if(item.provide_id === this.provide_id)
                    this.allSumm = this.allSumm + (item.price * item.count);
            });
        },
        cart_delete: function(item){
            emit('cart_delete', item);
        }
    },
    mounted() {
        this.calc();
    }
}
</script>

<template>
    <div class="cart">
        <div style="">
            <div  class="info-item" v-for="(item, index) in cart_items?.filter(items => items.provide_id === provide_id)">

                <div>
                    <div style="float: right;">
                        <span style="" v-on:click="cart_delete(item)">X</span>

                    </div>
                    <img v-on:click="changeCurrentInfoItem(infoItem.items.findIndex(item_full => item_full.id === item.item_id), item.attach_index)" :src="infoItem.items.find(item_full => item_full.id === item.item_id)?.attachments[item.attach_index]?.photo?.sizes[2]?.url">
                    <div class="price">
                        <button v-on:click="item.count--;">-</button>
                        <input type="text" v-model="cart_items.filter(items => items.provide_id === provide_id)[index].count">
                        <button v-on:click="item.count++;">+</button>
                        по
                        <input type="text" v-model="cart_items.filter(items => items.provide_id === provide_id)[index].price"> <b>руб.</b>
                    </div>
                </div>

            </div>
        </div>
        <div v-if="allSumm > 0" class="all_summ">
            <p>Общая сумма: <b>{{allSumm}} руб</b></p>
            <a :href="'/api/cart.make?id='+provide_id" target="_blank"><button class="button">Оформить заказ</button></a>
        </div>
    </div>
</template>

<style scoped>
.cart{
    height: 100%;
    min-width: 236px;
    overflow-y: scroll;
    overflow-x: hidden;
}
.price input[type=text]{
    width: 27px;
    text-align: center;
    border: 0px;
    padding: 3px;
    background: #50abf9;
    color: white;
    outline: none;
}
.info-item{
    width: 180px;
    margin: 5px;
    border: 1px solid #bbb;
    text-align: center;
    padding: 10px;
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
