<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    komik: Object,
    isBookmarked: Boolean,
});

const isBookmarked = ref(props.isBookmarked);

function bookmarkKomik() {
    router.post(
        '/bookmark',
        {
            comic_api_slug: props.komik.slug,
            comic_api_title: props.komik.title,
            comic_api_thumbnail: props.komik.thumbnail,
        },
        {
            onSuccess: () => {
                isBookmarked.value = true;
            },
        },
    );
}
</script>

<template>
    <div>
        <h1>{{ komik.title }}</h1>
        <img :src="komik.thumbnail" alt="" width="200" />
        <button @click="bookmarkKomik" :disabled="isBookmarked" style="margin-bottom: 16px"></button>
        <p>{{ komik.sinopsis }}</p>
        {{ isBookmarked ? 'Bookmarked' : 'Bookmark Komik' }}
        <!-- <p>Status: {{ komik.info['Status'] }}</p> -->
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
