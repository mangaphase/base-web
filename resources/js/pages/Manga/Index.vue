<template>
    <DefaultLayout>
        <h1 class="mb-4 text-2xl font-bold">Daftar Manga</h1>

        <div v-if="mangas.length === 0">Tidak ada data.</div>

        <ul v-else>
            <li v-for="item in mangas" :key="item.id" class="mb-2">
                <inertia-link :href="route('manga.show', item.id)">{{ item.title || item.name }}</inertia-link>
            </li>
        </ul>

        <div class="mt-6">
            <inertia-link v-if="filters.page > 1" :href="route('manga.index', { page: filters.page - 1 })">Prev</inertia-link>
            <inertia-link class="ml-4" :href="route('manga.index', { page: filters.page + 1 })">Next</inertia-link>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/inertia-vue3';
const { props } = usePage();
const mangas = props.value.mangas ?? [];
const filters = props.value.filters ?? { page: 1 };
</script>
