<script>
export default {
    props: ['providers'],
    methods: {
        favorite: async function(id){
            await post('providers.favorite', {id: id});
            await this.getList();
        }
    }
}
</script>

<template>
    <div v-for="(item, index) in providers" class="list_item">
        <div class="favorite" :class="{'favorite_active': item.favorites > 0}" v-on:click="favorite(item.id)">
        </div>
        <div class="icon" v-on:click="info(item.id);">
            <img :src="item.icon"></div>
        <div class="info" v-on:click="info(item.id);">{{item.name}}</div>
    </div>
</template>

<style scoped>

</style>
