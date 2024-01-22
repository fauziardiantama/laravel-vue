<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Home</h1>
                <p>This is the home page</p>
                <!-- make a vue link to route /about -->
                <router-link to="/about">About</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Home component mounted.')
        }
    }
</script>