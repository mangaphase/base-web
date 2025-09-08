<script setup>
defineProps({ bookmarks: Array });
</script>

<template>
    <div>
        <h1>Bookmark Komik</h1>
        <ul>
            <li v-for="b in bookmarks" :key="b.id">
                <a :href="`/manga/${b.comic_api_slug}`" style="text-decoration: none; color: inherit">
                    <img :src="b.comic_api_thumbnail" alt="" width="100" />
                    <span>{{ b.comic_api_title }}</span>
                </a>
            </li>
        </ul>
    </div>
</template>
