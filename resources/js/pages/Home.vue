<script setup>
defineProps({
    mangaPopuler: Array,
    manhwaPopuler: Array,
    manhuaPopuler: Array,
    genres: Array,
    terbaru: Array,
});
</script>
<template>
    <div>
        <h2>Terbaru</h2>
        <ul>
            <li v-for="t in terbaru" :key="t.mangaSlug">
                <a :href="`/manga/${t.mangaSlug}`">
                    <img :src="t.thumbnail" alt="" width="100" />
                    {{ t.title }} - {{ t.latestChapter }}
                </a>
            </li>
        </ul>

        <h2>Manga Populer</h2>
        <ul>
            <li v-for="m in mangaPopuler" :key="m.mangaSlug">
                <a :href="`/manga/${m.mangaSlug}`">
                    <img :src="m.thumbnail" alt="" width="100" />
                    {{ m.title }} - {{ m.latestChapter }}
                </a>
            </li>
        </ul>

        <h2>Manhwa Populer</h2>
        <ul>
            <li v-for="w in manhwaPopuler" :key="w.mangaSlug">
                <a :href="`/manga/${w.mangaSlug}`">
                    <img :src="w.thumbnail" alt="" width="100" />
                    {{ w.title }} - {{ w.latestChapter }}
                </a>
            </li>
        </ul>

        <h2>Manhua Populer</h2>
        <ul>
            <li v-for="h in manhuaPopuler" :key="h.mangaSlug">
                <a :href="`/manga/${h.mangaSlug}`">
                    <img :src="h.thumbnail" alt="" width="100" />
                    {{ h.title }} - {{ h.latestChapter }}
                </a>
            </li>
        </ul>
        <h1>Genre</h1>
        <ul>
            <li v-for="g in genres" :key="g.slug">
                <a :href="`/genre/${g.slug}`">{{ g.title }}</a>
            </li>
        </ul>
    </div>
</template>
