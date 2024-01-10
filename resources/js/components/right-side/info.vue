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
            currentInfoItem: 0,
            currentInfoItemAttach: 0,
        }
    },
    methods: {
        info: async function(id){
            let url = '/api/parser?type=vk&id='+id;
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
        <div class="desc">{{item.text}}</div>
    </div>
    <div class="full-info" :class="{'display-block': currentInfoItem}">
        <div class="full-info-bg" v-on:click="currentInfoItem = 0; currentInfoItemAttach = 0;"></div>
        <div class="info">
            <div>
                <div></div>
                <div style="display: flex">
                    <div v-for="(attach, index) in infoItem.items[currentInfoItem].attachments">
                        <img :src="attach.photo?.sizes[0]?.url">
                    </div>
                </div>
            </div>
            <div>{{infoItem.items[currentInfoItem]?.text}}</div>
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
.full-info .info{
    position: absolute;
    top: 10px;
    left: calc(50% - 350px);
    width: 700px;
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
