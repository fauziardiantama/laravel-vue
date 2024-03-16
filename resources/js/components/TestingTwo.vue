<template>
    <router-view />
</template>

<script>
export default {
    name: 'TestingTwo',
    beforeCreate() {
        console.log('beforeCreate')
        Echo.private('testing-channel').listen('TestingEvent', (e) => {
            console.log(e);
        });
    },
}
</script>