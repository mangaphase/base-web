<template>
    <DefaultLayout>
        <h1 class="text-2xl font-bold">{{ chapter.title || 'Chapter' }}</h1>

        <div v-if="chapter.pages && chapter.pages.length">
            <div v-for="(p, idx) in chapter.pages" :key="idx" class="mb-4">
                <img :src="p" alt="page" />
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/inertia-vue3';
const { props } = usePage();
const chapter = props.value.chapter ?? {};
</script>
