<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface Chapter {
    chapterNumber: string;
    title: string;
    views: number;
    date: string;
}

interface Komik {
    slug: string;
    title: string;
    thumbnail: string;
    sinopsis: string;
    genres: string[];
    chapters: Chapter[];
}

const props = defineProps({
    komik: {
        type: Object as () => Komik,
        required: true,
    },
    isBookmarked: {
        type: Boolean,
        default: false,
    },
    bookmarks: {
        type: Array,
        default: () => [],
    }
});

const isBookmarked = ref(props.isBookmarked);
const isLoading = ref(false);
const errorMessage = ref<string | null>(null);
const page = usePage();

// Watch for auth status changes
watch(() => page.props.auth?.user, (newVal) => {
    if (!newVal) {
        isBookmarked.value = false;
    }
}, { immediate: true });

// Watch for prop changes
watch(() => props.isBookmarked, (newVal) => {
    isBookmarked.value = newVal;
});

async function bookmarkKomik() {
    // Check if user is authenticated
    if (!page.props.auth?.user) {
        window.location.href = route('login');
        return;
    }

    try {
        isLoading.value = true;
        errorMessage.value = null;

        if (isBookmarked.value) {
            // Remove bookmark
            await router.delete(
                route('bookmark.destroy', { comic_api_slug: props.komik.slug }),
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        isBookmarked.value = false;
                        router.reload({ only: ['bookmarks'] });
                    },
                    onError: (errors) => {
                        console.error('Error removing bookmark:', errors);
                        errorMessage.value = 'Failed to remove bookmark. Please try again.';
                    },
                    onFinish: () => {
                        isLoading.value = false;
                    }
                }
            );
        } else {
            // Add bookmark
            await router.post(
                route('bookmark.store'),
                {
                    comic_api_slug: props.komik.slug,
                    comic_api_title: props.komik.title,
                    comic_api_thumbnail: props.komik.thumbnail,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        isBookmarked.value = true;
                        router.reload({ only: ['bookmarks'] });
                    },
                    onError: (errors) => {
                        console.error('Error adding bookmark:', errors);
                        errorMessage.value = 'Failed to add bookmark. Please try again.';
                    },
                    onFinish: () => {
                        isLoading.value = false;
                    }
                }
            );
        }
    } catch (error) {
        console.error('Error handling bookmark:', error);
        errorMessage.value = 'An unexpected error occurred. Please try again.';
        isLoading.value = false;
    }
}
</script>

<template>
    <AppHeader />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Cover and Bookmark -->
            <div class="md:w-1/3">
                <div class="relative">
                    <img
                        :src="komik.thumbnail"
                        :alt="komik.title"
                        class="w-full rounded-lg shadow-lg"
                    />
                    <div class="mt-4 space-y-2">
                        <button
                            @click="bookmarkKomik"
                            :disabled="isLoading"
                            class="w-full px-4 py-2 rounded-md font-medium text-white transition-all duration-200 relative"
                            :class="{
                                'bg-yellow-500 hover:bg-yellow-600': isBookmarked && !isLoading,
                                'bg-blue-600 hover:bg-blue-700': !isBookmarked && !isLoading,
                                'bg-gray-400 cursor-not-allowed': isLoading
                            }"
                        >
                            <span v-if="isLoading" class="absolute inset-0 flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span :class="{ 'invisible': isLoading }">
                                <span v-if="isBookmarked">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                    </svg>
                                    Bookmarked
                                </span>
                                <span v-else>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    Bookmark Komik
                                </span>
                            </span>
                        </button>

                        <!-- Error Message -->
                        <div v-if="errorMessage"
                             class="bg-red-50 border-l-4 border-red-400 p-4 transition-all duration-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ errorMessage }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button
                                            @click="errorMessage = null"
                                            class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        >
                                            <span class="sr-only">Dismiss</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details -->
            <div class="md:w-2/3">
                <h1 class="text-3xl font-bold mb-4">{{ komik.title }}</h1>

                <!-- Genre Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <a
                        v-for="genre in komik.genres"
                        :key="genre"
                        :href="`/genre/${genre.toLowerCase()}`"
                        class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors duration-200"
                    >
                        {{ genre }}
                    </a>
                </div>

                <!-- Synopsis -->
                <div class="prose max-w-none mb-8">
                    <p class="text-gray-700">{{ komik.sinopsis }}</p>
                </div>

                <!-- Chapters List -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Chapters</h2>
                    <div class="space-y-2">
                        <div
                            v-for="chapter in komik.chapters"
                            :key="chapter.chapterNumber"
                            class="flex items-center justify-between py-2 border-b last:border-b-0"
                        >
                            <a
                                :href="`/manga/${komik.slug}/${chapter.chapterNumber}`"
                                class="text-blue-600 hover:text-blue-800 font-medium"
                            >
                                {{ chapter.title || `Chapter ${chapter.chapterNumber}` }}
                            </a>
                            <div class="text-sm text-gray-500">
                                <span class="mr-4">👁 {{ chapter.views }}</span>
                                <span>📅 {{ chapter.date }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
