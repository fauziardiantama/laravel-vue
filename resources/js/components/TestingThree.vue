<template>
    {{ text }}
</template>

<script>
export default {
    name: 'TestingThree',
    props: {
        text: {
            type: String,
            default: 'Hello World'
        }
    },
    created() {
        console.log('created')
    }
}
</script>