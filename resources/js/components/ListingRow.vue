<template>
    <tr>
        <td>{{ listing.rank }}</td>
        <td><a :href="listing.link">{{ listing.title }}</a></td>
        <td>{{ listing.score }}</td>
        <td>{{ Date(listing.created_at) }}</td>
        <td><button><a :href="'delete/' + listing.id">Delete the listing</a></button></td>
    </tr>
</template>

<script>
    export default {
        name: 'listing-row',
        props: {
            listing: Object
        }
    }
</script>