<script>
export default{
    props: ['info']
}
</script>

<template>
<div v-for="(item, index) in info.items" style="
    border-bottom: 1px solid #bbb; padding: 5px;">
    <div  class="implode_list">
        <div style="width: 20%;">
            <img :src="item.info?.attachments[item.attach_index]?.photo?.sizes[2]?.url">
        </div>
        <div style="width: 80%;">
            <input type="text" v-model="item.name" placeholder="Наименование товара">
            <textarea v-model="item.info.text"></textarea>
        </div>
    </div>
    <div class="implode_list_options">
        <div class="option">
            <input type="number" v-model="item.count"> по
            <input type="number" v-model="item.price"> руб.
        </div>
        <div class="option">
            <span>Розница  </span>
            <input type="number"> руб.
        </div>
    </div>
</div>
</template>

<style scoped>
.implode_list{
    display: flex;
}
.implode_list > div > img{
    width: 100%;
    border-radius: 5px;
}
.implode_list > div{
    padding: 5px
}
input[type=text]{
    width: 100%;
    padding: 5px;
    border: 0px;
    border-bottom: 1px solid #bbb;
    font-family: Yasans;
}
textarea{
    padding: 5px;
    font-family: Yasans;
    border: 0px;
    border-bottom: 1px solid #bbb;
    width: 100%;
    height: 200px;
}
.implode_list_options{
    display: flex;
    justify-content: flex-end;
    width: 100%;
}
.implode_list_options .option{
    background: #2274bb;
    color: white;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 13px;
    margin: 3px;
}
.implode_list_options .option input[type=text], .implode_list_options .option input[type=number]{
    width: 35px;
    padding: 3px;
    background: #175081;
    border: 0px;
    color: white;
    font-size: 13px;
    font-weight: bold;
    border-radius: 3px;
    text-align: center;
}
</style>
