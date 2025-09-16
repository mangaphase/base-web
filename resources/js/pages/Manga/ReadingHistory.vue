<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const histories = usePage().props.histories;
</script>

<template>
    <div>
        <h2>Riwayat Baca</h2>
        <ul>
            <li v-for="history in histories" :key="history.id">
                <a :href="`/manga/${history.comic_api_slug}/${history.chapter_api_slug}`">
                    {{ history.comic_api_title }} - Chapter: {{ history.chapter_api_slug }} (Halaman terakhir: {{ history.last_page_number }})
                </a>
            </li>
        </ul>
    </div>
</template>
