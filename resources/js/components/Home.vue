<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Home</h1>
                <p>This is the home page</p>
                <p>{{ message }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                message: 'Hello Vue!'
            }
        },
        mounted() {
            Echo.private('testing-channel')
                .listen('TestingEvent', (e) => {
                    console.log(e);
                    this.message = e.message;
                });
        },
    }
</script>