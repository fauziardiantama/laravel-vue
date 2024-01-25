<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Home</h1>
                <p>This is the home page</p>
                <button 
                    class="btn btn-primary" 
                    data-bs-target="#collapseTarget" 
                    data-bs-toggle="collapse">
                    Bootstrap collapse
                </button>
                <div class="collapse py-2" id="collapseTarget">
                    This is the toggle-able content!
                </div>
                <!--make a form of two input "name" and "description"-->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" v-model="name">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="description"></textarea>
                </div>
                <!--make a button to submit the form-->
                <button class="btn btn-primary" @click="addItem">Create</button>
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
                posts: [],
                name: '',
                description: ''
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
            Echo.channel('items').listen('ItemAdded', (e) => {
                console.log(e);
                //replace posts with the new posts
                this.posts = e.item;
            });
            
        },
        methods: {
            addItem() {
                axios.post('http://127.0.0.1:8000/api/items', {
                    item : {
                        name: this.name,
                        description: this.description
                    }
                })
                    .then(response => {
                        console.log(response);
                        this.name = '';
                        this.description = '';
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>