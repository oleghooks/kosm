<script>

import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();

import {watch} from "vue";
export default {
    data(){
        return {
            infoItem: {
                items: {},
            },
            currentInfoItem: false,
            currentInfoItemAttach: 0,
        }
    },
    methods: {
        info: async function(id){
            this.infoItem.items = [];
            let url = '/api/providers.info?id='+id;
            let response = await fetch(url);
            this.infoItem.items = await response.json();
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
    <div class="info-item" v-for="(item, index) in infoItem.items">
        <div v-on:click="currentInfoItem = index"><img :src="item.attachments[0]?.photo?.sizes[2]?.url"></div>
        <div class="desc" v-html="item.text.replace(/([^>])\n/g, '$1<br/>')"></div>
    </div>
    <div class="full-info" :class="{'display-block': currentInfoItem !== false}">
        <div class="full-info-bg" v-on:click="currentInfoItem = false; currentInfoItemAttach = 0;"></div>
        <div class="info">
            <div style="width: 70%; border-right: 1px solid #bbb;">
                <div class="photo-full">
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
.info-item{
    display: flex;
    padding: 10px;
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
