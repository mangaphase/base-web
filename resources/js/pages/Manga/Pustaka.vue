<script setup>
defineProps({
    pustaka: Object,
});
</script>

<template>
    <div>
        <h1>Pustaka Manga</h1>
        <ul>
            <li v-for="item in pustaka.results" :key="item.detailUrl">
                <img :src="item.thumbnail" alt="" width="120" />
                <div>
                    <strong>{{ item.title }}</strong>
                    <p>{{ item.description }}</p>
                    <p>Genre: {{ item.genre }} | Tipe: {{ item.type }}</p>
                    <p>{{ item.stats }}</p>
                    <a :href="item.detailUrl">Detail</a>
                    <a v-if="item.latestChapter" :href="item.latestChapter.url" style="margin-left: 8px">
                        {{ item.latestChapter.title }}
                    </a>
                </div>
            </li>
        </ul>
        <div style="margin-top: 24px">
            <a v-if="pustaka.page > 1" :href="`/pustaka/${pustaka.page - 1}`">Previous</a>
            <a v-if="pustaka.results && pustaka.results.length" :href="`/pustaka/${pustaka.page + 1}`" style="margin-left: 16px"> Next </a>
        </div>
    </div>
</template>
