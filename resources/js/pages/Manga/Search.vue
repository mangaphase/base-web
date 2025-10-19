<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    query: String,
    result: Object,
});

const searchQuery = ref(props.query ?? '');

function submitSearch() {
    router.get('/search', { q: searchQuery.value });
}
</script>

<template>
    <AppHeader />
    <div>
        <form @submit.prevent="submitSearch" style="margin-bottom: 16px">
            <input v-model="searchQuery" type="text" placeholder="Cari manga..." style="padding: 8px; width: 250px" />
            <button type="submit" style="padding: 8px 16px">Cari</button>
        </form>

        <h2 v-if="props.query">Hasil Pencarian: "{{ props.query }}"</h2>
        <p v-if="props.result && props.result.total">Total: {{ props.result.total }}</p>
        <ul v-if="props.result && props.result.data && props.result.data.length">
            <li v-for="manga in props.result.data" :key="manga.slug">
                <a :href="`/manga/${manga.slug}`">
                    <img :src="manga.thumbnail" :alt="manga.title" width="100" style="vertical-align: middle; margin-right: 8px" />
                    {{ manga.title }}
                </a>
                <span>({{ manga.type }} - {{ manga.genre }})</span>
                <p>{{ manga.description }}</p>
            </li>
        </ul>
        <p v-else-if="props.query">Tidak ditemukan hasil untuk "{{ props.query }}"</p>
    </div>
</template>
