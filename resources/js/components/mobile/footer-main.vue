<script>
export default {
    props: [
        'currentPage',
        'changePage'
    ]
}
</script>

<template>
<div class="footer">
    <div :class="{'active': currentPage === 1}">
        <div class="home" v-on:click="changePage(1)"></div>
        <p>Главная</p>
    </div>
    <div :class="{'active': currentPage === 9}">
        <div class="favorites" v-on:click="changePage(9)"></div>
        <p>Избранное</p>
    </div>
    <div :class="{'active': currentPage === 6}">
        <div class="orders" v-on:click="changePage(6)"></div>
        <p>Заказы</p>
    </div>
    <div :class="{'active': currentPage === 7}">
        <div class="store" v-on:click="changePage(7)"></div>
        <p>Магазин</p>
    </div>
    <div :class="{'active': currentPage === 8}">
        <div class="profile" v-on:click="changePage(8)"></div>
        <p>Профиль</p>
    </div>
</div>
</template>

<style scoped>
.footer{
    position: fixed;
    background: #dbdbdbeb;
    width: calc(100% - 20px);
    bottom: 10px;
    height: 50px;
    display: flex;
    margin: 0 10px;
    border-radius: 10px;
    justify-content: space-between;
}
.footer > div > div{
    width: 28px;
    height: 28px;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    margin: 5px 20px 0px 20px;
}
/* #737373 - неактивный */
/* #2E5BAD - активный */
.footer > div > div.home{
    background-image: url("/img/icons/home.png");
}
.footer > div > p{
    margin-top: 0px;
    font-size: 9px;
    text-align: center;
}
.footer > div.active > p{
    color:#2E5BAD;
}
.footer > div.active >  div.home{
    background-image: url("/img/icons/home-active.png");
}
.footer > div >  div.favorites{
    background-image: url("/img/icons/favorites.png");
}
.footer > div.active >  div.favorites{
    background-image: url("/img/icons/favorites-active.png");
}
.footer > div >  div.orders{
    background-image: url("/img/icons/order.png");
}
.footer > div.active >  div.orders{
    background-image: url("/img/icons/order-active.png");
}
.footer > div >  div.profile{
    background-image: url("/img/icons/profile.png");
}
.footer > div.active >  div.profile{
    background-image: url("/img/icons/profile-active.png");
}
.footer > div >  div.store{
    background-image: url("/img/icons/store.png");
}
.footer > div.active >  div.store{
    background-image: url("/img/icons/store-active.png");
}
</style>
