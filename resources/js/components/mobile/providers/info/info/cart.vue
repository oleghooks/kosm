<script>
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
export default {
    props: [
        'changeCurrentInfoItem',
        'cart_items',
        'info',
        'provider_id',
        'back'
    ],
    data(){
        return {
            allSumm: 0,
            allCount: 0,
            items: [],
        }
    },
    watch: {
        cart_items: {
            deep: true,
            handler(newCartItems, oldCartItems) {
                this.calc();
            }
        },
        info: {
            deep: true,
            handler(newCartItems, oldCartItems) {
                this.calc();
            }
        }
    },
    methods: {
        calc: function(){

            this.allSumm = 0;
            this.allCount = 0;
            this.items.forEach(item => {
                this.allSumm = this.allSumm + (item.price * item.count);
                this.allCount = this.allCount + item.count;
            });
        },
        select_item: function(index){
            emit('select_item', index);
        },
        make_cart: function(){
            emit('make_cart', this.provider_id);
        },
        cart_delete: function(item){
            emit('cart_delete', item);
            this.items.splice(this.items.findIndex(items => items === item), 1);
        },
        cart_save: function(index){
            emit('cart_add', this.items[index]);
        },
        cart_items_load: async function (){
            let response = await fetch('/cart.info?provider_id='+this.provider_id);
            this.items = await response.json();
            this.calc();
        }
    },
    mounted() {
        this.cart_items_load();
    }
}
</script>

<template>
    <div class="back" v-on:click="back"><</div>
    <div class="cart">
        <div  class="info-item" v-for="(item, index) in items">

            <div>
                <div style="float: right;">
                <span style="" v-on:click="cart_delete(item)">X</span>

            </div>
                <img v-on:click="select_item()" :src="item.info?.attachments[item.attach_index]?.photo?.sizes[2]?.url">
                <div class="price">
                    <button v-on:click="item.count--; cart_save(index)">-</button>
                    <input type="text" v-model="item.count"  @keyup="cart_save(index)">
                    <button v-on:click="item.count++; cart_save(index)">+</button>
                    по
                    <input type="text" v-model="item.price" @keyup="cart_save(index)"> <b>руб.</b>
                </div>
            </div>

        </div>
    </div>
    <div v-if="allSumm > 0" class="all_summ">
        <p>{{ allCount }} товаров на сумму <b>{{allSumm}} руб</b></p>
        <button v-on:click="make_cart" class="button button-blue">Оформить заказ</button>
    </div>
</template>

<style scoped>
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
