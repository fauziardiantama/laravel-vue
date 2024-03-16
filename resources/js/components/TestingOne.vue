<template>
    {{ text }}
</template>

<script>
export default {
    name: 'TestingOne',
    data() {
        return {
            text: 'Hello World'
        }
    },
    created() {
        console.log('created')
        Echo.private('testing-channel').listen('TestingEvent', (e) => {
            this.text = e;
        });
    }
}
</script>