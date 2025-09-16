<script setup>
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    chapter: Object,
});

const chapter = props.chapter;

const user = usePage().props.auth?.user;

onMounted(() => {
    if (!user) return;

    // Pastikan chapter.mangaInfo dan chapter.meta ada
    if (!chapter.mangaInfo || !chapter.meta) return;

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch('/reading-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            comic_api_title: chapter.mangaInfo.title,
            comic_api_slug: chapter.mangaInfo.slug,
            chapter_api_slug: chapter.meta.chapterNumber,
            last_page_number: 1,
        }),
    });
});
</script>

<template>
    <div>
        <h1>{{ chapter.title }}</h1>
        <p>{{ chapter.description }}</p>
        <div>
            <strong>Judul:</strong> {{ chapter.chapterInfo?.Judul }}<br />
            <strong>Tanggal Rilis:</strong> {{ chapter.chapterInfo?.['Tanggal Rilis'] }}<br />
        </div>
        <div v-if="chapter.images && chapter.images.length">
            <div v-for="img in chapter.images" :key="img.id">
                <img :src="img.src" :alt="img.alt" style="max-width: 100%; margin-bottom: 16px" />
            </div>
        </div>
        <div v-else>
            <p>Gambar tidak tersedia.</p>
        </div>
        <div style="margin-top: 16px">
            <a
                v-if="chapter.navigation?.prevChapter"
                :href="`/manga/${chapter.navigation.prevChapter.slug}/${chapter.navigation.prevChapter.chapter}`"
            >
                Previous Chapter
            </a>
            <a
                v-if="chapter.navigation?.nextChapter"
                :href="`/manga/${chapter.navigation.nextChapter.slug}/${chapter.navigation.nextChapter.chapter}`"
                style="margin-left: 16px"
            >
                Next Chapter
            </a>
            <a v-if="chapter.navigation?.allChapters" :href="chapter.navigation.allChapters" style="margin-left: 16px"> Semua Chapter </a>
        </div>
        <div style="margin-top: 16px">
            <p>{{ chapter.additionalDescription }}</p>
        </div>
    </div>
</template>
