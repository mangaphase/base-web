<template>
    <DefaultLayout>
        <h1 class="text-2xl font-bold">{{ manga.title || manga.name }}</h1>
        <p v-if="manga.description">{{ manga.description }}</p>

        <div v-if="manga.chapters && manga.chapters.length">
            <h2 class="mt-4">Chapters</h2>
            <ul>
                <li v-for="ch in manga.chapters" :key="ch.id">
                    <inertia-link :href="route('manga.chapter', ch.id)">{{ ch.title }}</inertia-link>
                </li>
            </ul>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/inertia-vue3';
const { props } = usePage();
const manga = props.value.manga ?? {};
</script>
