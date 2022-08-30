<template>
    <span>{{ listing.id }}</span>
</template>

<script>
    export default {
        name: 'listing-table',
        props: {
            listing: Object
        },
        mounted() {
            console.log(this.listing);
        }
    }
</script>