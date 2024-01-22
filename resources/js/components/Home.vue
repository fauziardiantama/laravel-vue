<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Home</h1>
                <p>This is the home page</p>
                <ul>
                    <li v-for="post in posts" :key="post.id">
                        <h3>{{ post.name }}</h3>
                        <p>{{ post.description }}</p>
                    </li>
                </ul>
                <!-- make a vue link to route /about -->
                <router-link to="/about">About</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                posts: []
            }
        },
        async created() {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/items');
                this.posts = response.data.data;
            } catch (e) {
                console.log(e);
            }
        },
        mounted() {
            console.log('Home component mounted.')
        }
    }
</script>