<script>
import Item from "@/components/mobile/preorders/item.vue";

export default {
    components: {Item},
    data(){
        return {
            orders: {}
        }
    },
    methods: {
        list: async function(){
            let response = await fetch('/preorders.list');
            this.orders = await response.json();
        }
    },
    mounted() {
        this.list();
    }
}
</script>

<template>
    <div class="items">
        <item v-for="(item, index) in orders" :item="item" :list="list" />
    </div>
</template>

<style scoped>
    .items{
        display: flex;
        flex-wrap: wrap;
    }
</style>
