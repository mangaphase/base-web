<script setup>
import { Link } from '@inertiajs/vue3';
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
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
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
