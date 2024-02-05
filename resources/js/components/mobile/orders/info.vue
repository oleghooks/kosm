<script>
import ProvideInfo from "@/components/right-side/info/provide-info.vue";

export default {
    components: {ProvideInfo},
    props: ['info'],
    data(){

    },
    methods: {

    },
    mounted() {

    }
}
</script>

<template>

    <provide-info v-if="info.provider_info" :infoProvider="info.provider_info" />
</template>

<style scoped>

</style>
