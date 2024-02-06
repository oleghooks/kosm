<script>
import Implode from "@/components/mobile/store/implode.vue";

export default {
    components: {Implode},
    data(){
        return{
            currentPage: 0,
        }
    }
}
</script>

<template>
    <div v-if="currentPage === 0">
        <button class="button-blue button" v-on:click="currentPage = 1;">Загрузить товары</button>
    </div>
    <implode  v-if="currentPage === 1" />
</template>

<style scoped>

</style>
