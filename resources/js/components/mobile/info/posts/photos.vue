<script>
export default {
    props: ['item'],
    data(){
        return{
            timer: null,
            indexPhoto: 0,
            isCart: false
        }
    },
    methods: {
        clickPhoto: function(item){
            if(!this.timer)
            {
                this.timer = setTimeout(() => {
                    // simple click
                    this.timer = null;
                    if(this.isCart === true)
                        this.isCart = false;
                    else{
                        if((item.attachments.length - 1) === this.indexPhoto)
                            this.indexPhoto = 0;
                        else
                            this.indexPhoto++;
                    }
                }, 300);//tolerance in ms
            }else{
                clearTimeout(this.timer);
                this.timer = null;
                this.isCart = true;
                // double click
            }
        }
    }
}
</script>

<template>
    <div :class="{'cart_active': isCart}">
        <div class="cart">
            <div>
                <div>
                    <input type="number"><label>шт.</label>
                    <input type="number"><label>цена</label>
                </div>
                <div>
                    <button class="button button-blue">Добавить</button>
                    <button class="button button-grey" v-on:click="isCart = false;">Отменить</button>
                </div>
            </div>
        </div>
        <img :src="item.attachments[indexPhoto]?.photo?.sizes[2]?.url" v-on:click="clickPhoto(item)">
    </div>
    <div style="display: flex" class="photos">
        <div v-for="(attach, index) in item.attachments"  :class="{'photo-active': indexPhoto === index}">
            <!--<span v-if="fast_cart_items[index]">{{fast_cart_items[index]}}</span>-->
            <img :src="attach.photo?.sizes[0]?.url" v-on:click="indexPhoto = index">
        </div>
    </div>
</template>

<style scoped>
.cart_active .cart{
    display: block;
}
.cart{
    width: 300px;
    /* position: relative; */
    height: 0px;
    margin: 0 auto;
    background: #fff;
    display: none;
}
.cart > div{
    width: 300px;
    height: 100px;
    background: #ffffffeb;
    position: relative;
    margin-top: 0;
    top: 20px;
    border-radius: 5px;
    text-align: center;
    padding-top: 20px;
}
.cart input{
    padding: 5px;
    border: 1px solid #2559a7;
    outline: none;
    border-radius: 5px 0px 0px 5px;
    border-right: 0px;
    width: 50px;
    height: 29px;
    margin-left: 5px;
}
.cart label{
    background: #2f90b5;
    color: white;
    padding: 4px;
    border-radius: 0px 5px 5px 0px;
    border: 1px solid #2559a7;
    border-left: 0px;
    margin-right: 5px;
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
</style>
