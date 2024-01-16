<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

import {watch} from "vue";
export default {
    props: {
        cart_items
    },
    data(){
        return {
            infoItem: {
                items: {},
            },
            infoProvider: {

            },
            currentInfoItem: false,
            currentInfoItemAttach: 0,
            currentOrder: 'popular',
            currentId: 0,
            currentShopCart: {
                currentStatus: 'shop',
            }
        }
    },
    methods: {
        info: async function(id){
            this.currentId = id;
            this.infoItem.items = [];
            this.infoProvider = {};
            let url = '/api/providers.info?id='+id+'&order='+this.currentOrder;
            let response = await fetch(url);
            response = await response.json()
            this.infoItem.items = response.items;
            this.infoProvider = response.provider;
        },
        cart_add: function(){
            
        }
    },
    mounted() {
        const { bus } = useEventsBus()

        watch(()=>bus.value.get('info'), (val) => {
            // destruct the parameters
            const [id] = val ?? 0
            this.info(id);
        })
    }
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
    <div style="display: flex" class="orders" v-if="infoItem.items.length > 0">
        <div :class="{'order_active': currentOrder === 'post_date'}" v-on:click="currentOrder = 'post_date'; info(currentId);">По дате</div>
        <div :class="{'order_active': currentOrder === 'popular'}" v-on:click="currentOrder = 'popular'; info(currentId);">По популярности</div>
    </div>
    <div class="info-item" v-for="(item, index) in infoItem.items">
        <div style="display: flex; padding: 10px">
            <div v-on:click="currentInfoItem = index"><img :src="item.attachments[0]?.photo?.sizes[2]?.url"></div>
            <div class="desc" v-html="item.text.replace(/([^>])\n/g, '$1<br/>')"></div>
        </div>
        <div style="display: flex" class="info-item-stats">
            <div class="views"><svg fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg"><g fill="currentColor"><path d="M9.5 8a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path><path clip-rule="evenodd" d="M15.5 8c0-1-3-5-7.5-5S.5 7 .5 8s3 5 7.5 5 7.5-4 7.5-5zm-4 0a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z" fill-rule="evenodd"></path></g></svg> {{item.views}}</div>
            <div class="likes"><svg height="16" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path d="M16 4a5.95 5.95 0 0 0-3.89 1.7l-.12.11-.12-.11A5.96 5.96 0 0 0 7.73 4 5.73 5.73 0 0 0 2 9.72c0 3.08 1.13 4.55 6.18 8.54l2.69 2.1c.66.52 1.6.52 2.26 0l2.36-1.84.94-.74c4.53-3.64 5.57-5.1 5.57-8.06A5.73 5.73 0 0 0 16.27 4zm.27 1.8a3.93 3.93 0 0 1 3.93 3.92v.3c-.08 2.15-1.07 3.33-5.51 6.84l-2.67 2.08a.04.04 0 0 1-.04 0L9.6 17.1l-.87-.7C4.6 13.1 3.8 11.98 3.8 9.73A3.93 3.93 0 0 1 7.73 5.8c1.34 0 2.51.62 3.57 1.92a.9.9 0 0 0 1.4-.01c1.04-1.3 2.2-1.91 3.57-1.91z" fill="currentColor" fill-rule="nonzero"></path></g></svg> {{item.likes}}</div>
            <div class="reposts"><svg height="16" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path d="M12 3.73c-1.12.07-2 1-2 2.14v2.12h-.02a9.9 9.9 0 0 0-7.83 10.72.9.9 0 0 0 1.61.46l.19-.24a9.08 9.08 0 0 1 5.84-3.26l.2-.03.01 2.5a2.15 2.15 0 0 0 3.48 1.69l7.82-6.14a2.15 2.15 0 0 0 0-3.38l-7.82-6.13c-.38-.3-.85-.46-1.33-.46zm.15 1.79c.08 0 .15.03.22.07l7.82 6.14a.35.35 0 0 1 0 .55l-7.82 6.13a.35.35 0 0 1-.57-.28V14.7a.9.9 0 0 0-.92-.9h-.23l-.34.02c-2.28.14-4.4.98-6.12 2.36l-.17.15.02-.14a8.1 8.1 0 0 1 6.97-6.53.9.9 0 0 0 .79-.9V5.87c0-.2.16-.35.35-.35z" fill="currentColor" fill-rule="nonzero"></path></g></svg> {{item.reposts}}</div>
            <div class="comments"><svg height="16   " viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M0 0h24v24H0z"></path><path d="M16.9 4H7.1c-1.15 0-1.73.11-2.35.44-.56.3-1 .75-1.31 1.31C3.11 6.37 3 6.95 3 8.1v5.8c0 1.15.11 1.73.44 2.35.3.56.75 1 1.31 1.31l.15.07c.51.25 1.04.35 1.95.37h.25v2.21c0 .44.17.85.47 1.16l.12.1c.64.55 1.6.52 2.21-.08L13.37 18h3.53c1.15 0 1.73-.11 2.35-.44.56-.3 1-.75 1.31-1.31.33-.62.44-1.2.44-2.35V8.1c0-1.15-.11-1.73-.44-2.35a3.17 3.17 0 0 0-1.31-1.31A4.51 4.51 0 0 0 16.9 4zM6.9 5.8h9.99c.88 0 1.18.06 1.5.23.25.13.44.32.57.57.17.32.23.62.23 1.5v6.16c-.02.61-.09.87-.23 1.14-.13.25-.32.44-.57.57-.32.17-.62.23-1.5.23h-4.02a.9.9 0 0 0-.51.26l-3.47 3.4V17.1c0-.5-.4-.9-.9-.9H6.74a2.3 2.3 0 0 1-1.14-.23 1.37 1.37 0 0 1-.57-.57c-.17-.32-.23-.62-.23-1.5V7.74c.02-.61.09-.87.23-1.14.13-.25.32-.44.57-.57.3-.16.58-.22 1.31-.23z" fill="currentColor" fill-rule="nonzero"></path></g></svg> {{item.comments}}</div>
            <div>{{item.post_date}}</div>
        </div>
    </div>
    <div class="full-info" :class="{'display-block': currentInfoItem !== false}">
        <div class="full-info-bg" v-on:click="currentInfoItem = false; currentInfoItemAttach = 0; currentShopCart.currentStatus = 'shop';"></div>
        <div class="info">
            <div style="width: 70%; border-right: 1px solid #bbb;">
                <div class="photo-full">
                    <div class="dialog_shop_cart" v-if="currentShopCart.currentStatus === 'dialog'">
                        <input type="text" placeholder="Кол.">
                        <input type="text" placeholder="Цена">
                        <button v-on:click="currentShopCart.currentStatus = 'shop'">Добавить</button>
                    </div>
                    <div class="shop_cart" v-on:click="currentShopCart.currentStatus = 'dialog'" v-if="currentShopCart.currentStatus === 'shop'"></div>
                    <img :src="infoItem.items[currentInfoItem]?.attachments[currentInfoItemAttach]?.photo?.sizes.at(-1).url">
                </div>
                <div style="display: flex" class="photos">
                    <div v-for="(attach, index) in infoItem?.items[currentInfoItem]?.attachments"  :class="{'photo-active': currentInfoItemAttach === index}">
                        <img :src="attach.photo?.sizes[0]?.url" v-on:click="currentInfoItemAttach = index">
                    </div>
                </div>
            </div>
            <div style="padding: 10px;" v-html="infoItem.items[currentInfoItem]?.text.replace(/([^>])\n/g, '$1<br/>')"></div>
        </div>
    </div>
</template>

<style>
.dialog_shop_cart{
    position: absolute;
    right: 30%;
    padding: 10px;
    background: #ffffff94;
    border-radius: 3px;
}
.dialog_shop_cart input[type=text]{
    border: 1px solid #bbb;
    padding: 8px;
    width: 92px;
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
    right: 30%;
    position: absolute;
    opacity: 0.7;
}
.shop_cart:hover{
    opacity: 1;
    cursor: pointer;
}
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
.info-item-stats{
    width: 100%;
    background: #8bbdd14d;

    padding: 10px;
    color: #18354e;
    font-weight: bold;
}
.info-item-stats > div{
    padding: 4px 16px;
    margin-right: 15px;
    background: #2b5f92;
    border-radius: 5px;
    color: white;
}
.orders > div{
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
}

.orders > div:hover{
    background: #bbb;
}

.orders > .order_active{
    background: #bbb;
}
.info-item{
    border-bottom: 1px solid #bbb;
}
.info-item img{
    width: 128px;
    border-radius: 7px;
}
.info-item .desc{
    padding: 10px;
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
