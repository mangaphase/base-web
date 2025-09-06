<script setup>
defineProps({
    chapter: Object,
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
