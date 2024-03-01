<script>
import {post} from "@/post.js";

export default {
    data(){
        return{
            chat_bot: {
                cookie: "",
                chat_id: "",
            }
        }
    },
    methods: {
        load: async function (){
            let response = await fetch('/chatbot.settings');
            response = await response.json();
            this.chat_bot.cookie = response.cookie;
            this.chat_bot.chat_id = response.chat_id;
        },
        save: async function(){
            await post('/chatbot.settings', this.chat_bot);
        }
    },
    mounted() {
        this.load();
    }
}
</script>

<template>
    <p>Cookie VK</p>
    <textarea v-model="chat_bot.cookie"></textarea>
    <p>ID чата VK</p>
    <input type="text" v-model="chat_bot.chat_id">
    <p><button class="button button-blue" v-on:click="save">Сохранить</button></p>
</template>

<style scoped>

</style>
