<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>About</h1>
                <p>This is the about page</p>
                <!-- make a vue link to route / -->
                <router-link to="/">Home</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('About component mounted.')
        }
    }
</script>