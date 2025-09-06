<script setup>
defineProps({
    komik: Object,
});
</script>

<template>
    <div>
        <h1>{{ komik.title }}</h1>
        <img :src="komik.thumbnail" alt="" width="200" />
        <p>{{ komik.sinopsis }}</p>
        <p>Status: {{ komik.info['Status'] }}</p>
        <p>Genre: {{ komik.genres }}</p>
        <h2>Chapters</h2>
        <ul>
            <li v-for="chapter in komik.chapters" :key="chapter.chapterNumber">
                <a :href="`/manga/${komik.slug}/${chapter.chapterNumber}`">
                    {{ chapter.title || `Chapter ${chapter.chapterNumber}` }}
                </a>
                <span> | Views: {{ chapter.views }} | Date: {{ chapter.date }}</span>
            </li>
        </ul>
    </div>
</template>
