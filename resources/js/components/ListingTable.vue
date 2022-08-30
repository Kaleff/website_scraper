<template>
    <table>
        <tr>
            <th>Rank</th>
            <th>Title</th>
            <th>Points</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <template v-for="listing in listings"> 
            <ListingRow :listing="listing"></ListingRow>
        </template>
    </table>
</template>

<script>
    import ListingRow from './ListingRow.vue'

    export default {
        name: 'listing-table',
        props: {
            listings: Array
        },
        components: {
            ListingRow
        },
        mounted() {
            //
        }
    }
</script>