<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import { router } from '@inertiajs/vue3';

interface Bookmark {
    id: number;
    comic_api_slug: string;
    comic_api_title: string;
    comic_api_thumbnail: string;
}

defineProps({
    bookmarks: {
        type: Array as () => Bookmark[],
        required: true
    }
});

function removeBookmark(slug: string) {
    router.delete(
        route('bookmark.destroy', { comic_api_slug: slug }),
        {
            preserveScroll: true,
            preserveState: false, // Set to false to force a full page refresh
            onBefore: () => confirm('Are you sure you want to remove this bookmark?'),
            onSuccess: () => {
                // The page will automatically refresh
            },
            onError: (errors) => {
                console.error('Error removing bookmark:', errors);
            }
        }
    );
}
</script>

<template>
    <AppHeader />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-900">Bookmark Komik</h1>

        <div v-if="bookmarks.length === 0" class="text-center py-8">
            <p class="text-gray-500">Belum ada komik yang di-bookmark</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div v-for="bookmark in bookmarks"
                 :key="bookmark.id"
                 class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                <a :href="`/manga/${bookmark.comic_api_slug}`" class="block">
                    <div class="relative aspect-[2/3]">
                        <img :src="bookmark.comic_api_thumbnail"
                             :alt="bookmark.comic_api_title"
                             class="absolute inset-0 w-full h-full object-cover" />
                    </div>
                </a>

                <div class="p-4">
                    <a :href="`/manga/${bookmark.comic_api_slug}`"
                       class="block text-lg font-medium text-gray-900 hover:text-blue-600 truncate mb-2">
                        {{ bookmark.comic_api_title }}
                    </a>

                    <button
                        @click="removeBookmark(bookmark.comic_api_slug)"
                        class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md font-medium transition-colors duration-200 flex items-center justify-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 mr-1"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
                        </svg>
                        Hapus Bookmark
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
