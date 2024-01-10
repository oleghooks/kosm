<script>
import useEventsBus from "@/EventBus.js";
const {emit}=useEventsBus();
export default {
    data(){
        return{
            providers: [
                {
                    name: 'САДОВОД КОСМЕТИКА',
                    parser_type: 'vk',
                    parser_id: '-122557292',
                    icon: 'https://sun83-2.userapi.com/s/v1/if2/3YGndDcufAIDUzmL2yIqgyOVExh-4iQzrrwbzfUspZ3vYVZUWwFdfV2gmMvbzJBWFPIV3iu5_vOhjMLn2U_8DpZu.jpg?quality=95&crop=117,0,400,400&as=50x50,100x100,200x200,400x400&ava=1&u=kmrXVUl2OQAdG0CO4ntUWr8ZePPatDZNh385tJYCFro&cs=100x100'
                }
            ]
        }
    },
    methods: {
        info: function(id){
            emit('info', id);
        }
    }
}
</script>

<template>
<div v-for="item in providers" class="list_item" v-on:click="info(item.parser_id)">
    <div class="icon"><img :src="item.icon"></div>
    <div class="info">{{item.name}}</div>
</div>
</template>

<style>
.list_item{
    display: flex;
    padding: 10px;
    border-bottom: 1px solid #bbb;
}
.list_item .info{
    margin-left: 12px;
}
.list_item .icon img{
    width: 40px;
    height: 40px;
    border-radius: 100%;
}
</style>
