<script>
import list from "./left-side/list.vue";
import info from "./right-side/info.vue";

export default {
    components: {
        'list': list,
        'info': info,
    }
}
</script>

<template>
    <div class="main">
        <div class="menu">
            <div icon="home"></div>
        </div>
        <div class="left-side">
            <list></list>
        </div>
        <div class="right-side">
            <info></info>
        </div>
    </div>
</template>

<style>
body{
    margin: 0px;
    height: 100%;
}
.main{
    display: flex;
    height: 100%;
}


.menu{
    width: 80px;
    background: #4b5563;
}
.menu > div{
    width: 40px;
    height: 40px;
    background-size: 100% 100%;
    margin: 8px;
}
div[icon=home]{
    background-image: url('/img/icons/home_icon.png');
}



.left-side{
    width: 400px;
    height: 100%;
    border-right: 1px solid #bbb;
}
.right-side{
    width: 100%;
    height: 100%;
    overflow-y: scroll;
}
body{
    font-family: Roboto;
}
</style>
