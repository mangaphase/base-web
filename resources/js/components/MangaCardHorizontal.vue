<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface MangaItem {
    mangaSlug: string;
    title: string;
    thumbnail: string;
    latestChapter?: string;
    latestChapterTitle?: string;
    genre?: string;
    updateTime?: string;
    updateCountText?: string;
    type?: 'Manga' | 'Manhwa' | 'Manhua';
    isColored?: boolean;
}

interface Props {
    manga: MangaItem;
}

defineProps<Props>();

const getTypeColor = (type?: string) => {
    switch (type?.toLowerCase()) {
        case 'manga':
            return 'bg-blue-500';
        case 'manhwa':
            return 'bg-purple-500';
        case 'manhua':
            return 'bg-orange-500';
        default:
            return 'bg-blue-500';
    }
};

const getGenreColor = (genre?: string) => {
    if (!genre) return 'bg-gray-600';

    // Extract genre name without reader count
    const genreName = genre.split(' ')[0].toLowerCase();

    switch (genreName) {
        case 'fantasi':
        case 'fantasy':
            return 'bg-purple-600';
        case 'aksi':
        case 'action':
            return 'bg-red-600';
        case 'martial':
            return 'bg-yellow-600';
        case 'isekai':
            return 'bg-green-600';
        case 'romantis':
        case 'romance':
            return 'bg-pink-600';
        case 'comedy':
            return 'bg-blue-600';
        default:
            return 'bg-gray-600';
    }
};
</script>

<template>
    <div class="group cursor-pointer">
        <Link :href="`/manga/${manga.mangaSlug}`" class="block">
            <div
                class="relative transform overflow-hidden rounded-xl bg-gray-800 shadow-lg transition-all duration-300 group-hover:scale-105 hover:shadow-xl"
            >
                <!-- Thumbnail -->
                <div class="relative aspect-[4/3]">
                    <img :src="manga.thumbnail" :alt="manga.title" class="h-full w-full object-cover" loading="lazy" />

                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                    <!-- Type badge -->
                    <div
                        v-if="manga.type"
                        :class="['absolute top-2 left-2 rounded px-2 py-1 text-xs font-semibold text-white', getTypeColor(manga.type)]"
                    >
                        {{ manga.type }}
                    </div>

                    <!-- Genre badge -->
                    <div
                        v-if="manga.genre"
                        :class="['absolute top-2 right-2 rounded px-2 py-1 text-xs font-semibold text-white', getGenreColor(manga.genre)]"
                    >
                        {{ manga.genre.split(' ')[0] }}
                    </div>

                    <!-- Chapter badge -->
                    <!-- <div
                        v-if="manga.latestChapterTitle || manga.latestChapter"
                        class="absolute bottom-2 left-2 rounded bg-blue-500 px-2 py-1 text-xs font-semibold text-white"
                    >
                        {{ manga.latestChapterTitle || manga.latestChapter }}
                    </div> -->

                    <!-- Update count -->
                    <!-- <div
                        v-if="manga.updateCountText"
                        class="absolute right-2 bottom-2 rounded bg-orange-500 px-2 py-1 text-xs font-semibold text-white"
                    >
                        {{ manga.updateCountText }}
                    </div> -->
                </div>

                <!-- Content overlay -->
                <div class="absolute right-0 bottom-0 left-0 p-3 text-white">
                    <h3 class="mb-1 line-clamp-2 text-sm leading-tight font-semibold transition-colors group-hover:text-blue-300">
                        {{ manga.title }}
                    </h3>

                    <div class="flex items-center justify-between text-xs text-gray-300">
                        <span v-if="manga.genre" class="truncate">
                            {{ manga.genre }}
                        </span>

                        <span v-if="manga.updateTime" class="ml-2 whitespace-nowrap text-gray-400">
                            {{ manga.updateTime }}
                        </span>
                    </div>
                </div>
            </div>
        </Link>
    </div>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
