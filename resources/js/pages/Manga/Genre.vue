<script setup>
defineProps({
    title: String,
    items: Array,
    slug: String,
    currentPage: Number,
    // totalManga: Number,
    hasNextPage: Boolean,
    nextPageUrl: String,
});
</script>

<template>
    <div>
        <h1>{{ title }}</h1>
        <!-- <p>Total Manga: {{ totalManga }}</p> -->
        <ul>
            <li v-for="item in items" :key="item.slug">
                <a :href="`/manga/${item.slug}`">
                    <img :src="item.thumbnail" alt="" width="100" />
                    {{ item.title }} - {{ item.type }} - {{ item.genre }}
                </a>
            </li>
        </ul>
        <div v-if="hasNextPage">
            <a :href="`/genre/${slug}/page/${currentPage + 1}`">Next Page</a>
        </div>
        <div v-else>
            <p>No more pages.</p>
        </div>
        <div style="margin-top: 16px">
            <a v-if="currentPage > 1" :href="currentPage === 2 ? `/genre/${slug}` : `/genre/${slug}/page/${currentPage - 1}`">Previous Page</a>
        </div>
    </div>
</template>
