<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
export default {
    props: [
        'currentInfoItem',
        'infoItem',
        'cart_items',
        'cart_add'
    ],
    watch: {
        infoItem: {
            handler(newInfoItem, oldInfoItem){
                this.calculate();
            },
            deep: true
        },
        cart_items: {
            handler(newCartItems, oldCartItems){
                this.calculate();
            },
            deep: true
        }
    },
    data(){
        return{
            currentInfoItemAttach: 0,
            currentShopCart: {
                status: 'shop',
                price: "",
                count: "",
                is_block_add: false,
            },
            fast_cart_items: {

            }

        }
    },
    methods: {
        cart_add_vue: function(){
            this.cart_add(this.infoItem.id, this.currentInfoItemAttach, this.currentShopCart.count, this.currentShopCart.price);
            this.changeAttach(this.currentInfoItemAttach)
        },
        calculate: function(){
            this.fast_cart_items = {};
            this.cart_items.forEach((item) => {
                if(item.item_id === this.infoItem?.id)
                    this.fast_cart_items[item.attach_index] = item.count;
            });
            this.changeAttach(this.currentInfoItemAttach);
        },
        changeAttach: function(index){
            this.currentInfoItemAttach = index;
            let price = '';
            let count = '';
            let status = 'shop';
            this.currentShopCart.is_block_add = false;
            this.cart_items.forEach((item) => {
                if(item.item_id === this.infoItem?.id && item.attach_index === index){
                    price = item.price;
                    count = item.count;
                    status = 'dialog';
                    this.currentShopCart.is_block_add = true;
                }
            });
            this.currentShopCart.status = status;
            this.currentShopCart.price = price;
            this.currentShopCart.count = count;
        },
        close: function(){
            emit('currentInfoItemFalse', 1);
            this.currentInfoItemAttach = 0;
            this.currentShopCart.status = 'shop';
            this.currentShopCart.price = '';
            this.currentShopCart.count = '';
        }
    }
}
</script>

<template>
    <div class="full-info" :class="{'display-block': currentInfoItem !== false}">
        <div class="full-info-bg" v-on:click="close"></div>
        <div class="info">
            <div style="width: 70%; border-right: 1px solid #bbb;">
                <div class="photo-full">
                    <div style="float: right;">
                        <div class="dialog_shop_cart" v-if="currentShopCart.status === 'dialog'">
                            <input v-model="currentShopCart.count" type="text" placeholder="Кол.">
                            <input v-model="currentShopCart.price" type="text" placeholder="Цена">
                            <button v-if="currentShopCart.is_block_add === false" v-on:click="cart_add_vue">Добавить</button>
                            <button v-if="currentShopCart.is_block_add === true" class="button_is_blocked">В корзине</button>
                        </div>
                        <div class="shop_cart" v-on:click="currentShopCart.status = 'dialog'" v-if="currentShopCart.status === 'shop'"></div>
                    </div>
                    <img :src="infoItem?.attachments[currentInfoItemAttach]?.photo?.sizes.at(-1).url">
                </div>
                <div style="display: flex" class="photos">
                    <div v-for="(attach, index) in infoItem?.attachments"  :class="{'photo-active': currentInfoItemAttach === index}">
                        <span v-if="fast_cart_items[index]">{{fast_cart_items[index]}}</span>
                        <img :src="attach.photo?.sizes[0]?.url" v-on:click="changeAttach(index)">
                    </div>
                </div>
            </div>
            <div style="padding: 10px;" v-html="infoItem?.text.replace(/([^>])\n/g, '$1<br/>')"></div>
        </div>
    </div>
</template>

<style scoped>
.dialog_shop_cart{
    position: absolute;
    margin-left: -256px;
    padding: 10px;
    background: #ffffff94;
    border-radius: 3px;
}
.dialog_shop_cart input[type=text]{
    border: 1px solid #bbb;
    padding: 8px;
    width: 67px;
    border-radius: 3px;
    margin: 0px 5px;
    outline: none;
}
.dialog_shop_cart button{
    background: #2274bb;
    border: 0px;
    padding: 9px;
    border-radius: 3px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    opacity: 0.7;
}
.dialog_shop_cart button:hover{
    opacity: 1;
}
.shop_cart{
    width: 48px;
    height: 48px;
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABzklEQVR4nO2XPUsDQRCGVxTsjAm4G+M3CPZWdraihSjOBLTRn2Bhp39A/BG2BsHK2kJiZoJon8pa/AwIYiKRPVLJ5S7m9rJ7eA9MdTB778ztvHNCpKSkeCigb4Xc+h0S6TMPd2PCdRRy00+AF0AHwnUUcqOTAIn8IKA0KFxGAX117AByK4+VVZEkJNJpkKC+BlD5zwLG8WbR+otju/tAW711Aahq++Wlvn/LV0M9CVDAu9YFAO2LXplfqQ1LpEeLAupZuM2IKEjkY2sCgE5EVPKb5ZlAk4svmgpoLrIArwvAl33/9pHOhCkU0JoFAUvGBAjRGlDINaeNKwy90DlvXEFMb19nJdKH08blwn4koxiXA/tRPbJxWd2PwIBxhaGwsheTgIYqVmZjFyCBj5w3rk7kdmhEIj8nwLi6rT69ZtbvR0USyPlUXyIfiqQg0+pbRMY0eVRAJGLyqH4IsFF9ZUqAreorkx1IcYlCsTqlgM/12usF8EUBqguu5As9zO8+SOAX/cx2vlDalfJfxIBLtvOF0m6z/9QAfredL9qBSG+284WiL5jJnxHT+ULR00FfMJ/DniY2aNJ2vu4nB3BJf6M6dKWiHFYwnC8l5T/yAw2X7CtlzVDmAAAAAElFTkSuQmCC);
    color: white;
    position: absolute;
    opacity: 0.7;
    margin-left: -50px;
}
.shop_cart:hover{
    opacity: 1;
    cursor: pointer;
}
.full-info{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    overflow-y: auto;
}
.full-info-bg{
    background: #bbbbbb73;
    width: 100%;
    height: 100%;
}
.photo-full{
    height: calc(100% - 100px);
    text-align: center;
}
.photo-full img{
    max-height: 100%;
}
.photos{
    padding: 10px;
    max-height: 80px;
}
.photos span{
    position: absolute;
    margin-left: 5px;
    background: red;
    color: white;
    font-weight: bold;
    padding: 4px;
    border-radius: 4px;
}
.photos > div{
    border: 3px solid #fff;
    border-radius: 3px;
    cursor: pointer;
}
.photos > div.photo-active{
    border: 3px solid #bbb;
}
.full-info .info{
    position: absolute;
    top: 10px;
    left: 75px;
    width: calc(100% - 150px);
    background: #fff;
    border-radius: 3px;
    border: 1px solid #bbb;
    height: calc(100% - 20px);
    overflow-y: auto;
    display: flex;
}
.display-block{
    display: block;
}
</style>
