<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    komik: Object,
    isBookmarked: Boolean,
});

const isBookmarked = ref(props.isBookmarked);
const showFullSynopsis = ref(false);

function bookmarkKomik() {
    router.post(
        '/bookmark',
        {
            comic_api_slug: props.komik.slug,
            comic_api_title: props.komik.title,
            comic_api_thumbnail: props.komik.thumbnail,
        },
        {
            onSuccess: () => {
                isBookmarked.value = true;
            },
        },
    );
}

function toggleSynopsis() {
    showFullSynopsis.value = !showFullSynopsis.value;
}
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-b from-slate-900 via-slate-800 to-transparent">
            <!-- Manga Background -->
            <div class="absolute inset-0 overflow-hidden">
                <img :src="komik.thumbnail" :alt="komik.title" class="h-full w-full scale-110 object-cover object-center blur-sm" />
            </div>
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-slate-900/95"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-slate-900/60"></div>
            <div class="relative z-10 mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="flex flex-col items-start gap-8 lg:flex-row">
                    <!-- Cover Image -->
                    <div class="flex-shrink-0">
                        <div class="group relative">
                            <img
                                :src="komik.thumbnail"
                                :alt="komik.title"
                                class="h-96 w-64 rounded-2xl object-cover shadow-2xl transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 rounded-2xl bg-gradient-to-t from-yellow-400/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                            ></div>
                        </div>
                    </div>

                    <!-- Manga Info -->
                    <div class="flex-1 text-white">
                        <div class="mb-6">
                            <h1 class="mb-4 text-4xl leading-tight font-bold lg:text-5xl">{{ komik.title }}</h1>
                            <div class="mb-6 flex flex-wrap items-center gap-4">
                                <span class="rounded-full bg-yellow-500 px-3 py-1 text-sm font-medium text-slate-900">
                                    {{ komik.info?.Status || 'Ongoing' }}
                                </span>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="text-sm">4.8 (2.1k reviews)</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-eye text-yellow-300"></i>
                                    <span class="text-sm">1.2M views</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mb-8 flex flex-wrap gap-4">
                            <a
                                :href="`/manga/${komik.slug}/${komik.chapters?.[0]?.chapterNumber || '1'}`"
                                class="transform rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 px-8 py-3 text-lg font-semibold text-slate-900 shadow-lg transition-all duration-300 hover:scale-105 hover:from-yellow-500 hover:to-yellow-600"
                            >
                                <i class="fas fa-play mr-2"></i>
                                Start Reading
                            </a>
                            <button
                                @click="bookmarkKomik"
                                :disabled="isBookmarked"
                                :class="[
                                    'transform rounded-full px-6 py-3 font-medium shadow-lg transition-all duration-300 hover:scale-105',
                                    isBookmarked
                                        ? 'cursor-not-allowed bg-yellow-500 text-slate-900'
                                        : 'border border-yellow-400/50 bg-yellow-400/20 text-yellow-300 backdrop-blur-sm hover:bg-yellow-400/30',
                                ]"
                            >
                                <i :class="['mr-2', isBookmarked ? 'fas fa-heart' : 'far fa-heart']"></i>
                                {{ isBookmarked ? 'Bookmarked' : 'Bookmark' }}
                            </button>
                            <button
                                class="transform rounded-full border border-yellow-400/50 bg-yellow-400/20 px-6 py-3 font-medium text-yellow-300 shadow-lg backdrop-blur-sm transition-all duration-300 hover:scale-105 hover:bg-yellow-400/30"
                            >
                                <i class="fas fa-share-alt mr-2"></i>
                                Share
                            </button>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                            <div class="rounded-lg border border-yellow-400/30 bg-slate-800/60 p-4 text-center backdrop-blur-sm">
                                <div class="text-2xl font-bold text-yellow-400">{{ komik.chapters?.length || 0 }}</div>
                                <div class="text-sm opacity-80">Chapters</div>
                            </div>
                            <div class="rounded-lg border border-yellow-400/30 bg-slate-800/60 p-4 text-center backdrop-blur-sm">
                                <div class="text-2xl font-bold text-yellow-400">
                                    {{
                                        typeof komik.genres === 'string'
                                            ? komik.genres.split(',').length
                                            : Array.isArray(komik.genres)
                                              ? komik.genres.length
                                              : 0
                                    }}
                                </div>
                                <div class="text-sm opacity-80">Genres</div>
                            </div>
                            <div class="rounded-lg border border-yellow-400/30 bg-slate-800/60 p-4 text-center backdrop-blur-sm">
                                <div class="text-2xl font-bold text-yellow-400">1.2M</div>
                                <div class="text-sm opacity-80">Total Views</div>
                            </div>
                            <div class="rounded-lg border border-yellow-400/30 bg-slate-800/60 p-4 text-center backdrop-blur-sm">
                                <div class="text-2xl font-bold text-yellow-400">4.8</div>
                                <div class="text-sm opacity-80">Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="space-y-8 lg:col-span-2">
                    <!-- Synopsis -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                        <h2 class="mb-6 flex items-center text-2xl font-bold text-slate-900">
                            <i class="fas fa-book-open mr-3 text-yellow-500"></i>
                            Synopsis
                        </h2>
                        <div class="prose prose-lg max-w-none">
                            <p
                                :class="[
                                    'leading-relaxed text-slate-700 transition-all duration-300',
                                    !showFullSynopsis && komik.sinopsis?.length > 300 ? 'line-clamp-4' : '',
                                ]"
                            >
                                {{ komik.sinopsis }}
                            </p>
                            <button
                                v-if="komik.sinopsis?.length > 300"
                                @click="toggleSynopsis"
                                class="mt-4 font-medium text-yellow-600 transition-colors hover:text-yellow-700"
                            >
                                {{ showFullSynopsis ? 'Show Less' : 'Read More' }}
                                <i
                                    :class="[
                                        'ml-1 transition-transform duration-200',
                                        showFullSynopsis ? 'fas fa-chevron-up' : 'fas fa-chevron-down',
                                    ]"
                                ></i>
                            </button>
                        </div>
                    </div>

                    <!-- Chapter List -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="flex items-center text-2xl font-bold text-slate-900">
                                <i class="fas fa-list mr-3 text-yellow-500"></i>
                                Chapters ({{ komik.chapters?.length || 0 }})
                            </h2>
                            <div class="flex items-center gap-2">
                                <button class="p-2 text-slate-500 transition-colors hover:text-yellow-600">
                                    <i class="fas fa-sort-amount-down"></i>
                                </button>
                                <button class="p-2 text-slate-500 transition-colors hover:text-yellow-600">
                                    <i class="fas fa-th-list"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div
                                v-for="chapter in komik.chapters"
                                :key="chapter.chapterNumber"
                                class="group flex items-center justify-between rounded-lg border border-transparent p-4 transition-colors hover:border-yellow-200 hover:bg-yellow-50"
                            >
                                <div class="flex flex-1 items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-yellow-100 to-yellow-200 font-semibold text-slate-700"
                                    >
                                        {{ chapter.chapterNumber }}
                                    </div>
                                    <div class="flex-1">
                                        <a
                                            :href="`/manga/${komik.slug}/${chapter.chapterNumber}`"
                                            class="block font-medium text-slate-900 transition-colors group-hover:text-yellow-600"
                                        >
                                            {{ chapter.title || `Chapter ${chapter.chapterNumber}` }}
                                        </a>
                                        <div class="mt-1 flex items-center gap-4 text-sm text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-calendar"></i>
                                                {{ chapter.date }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-eye"></i>
                                                {{ chapter.views }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="p-2 text-slate-400 opacity-0 transition-all group-hover:opacity-100 hover:text-yellow-600">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <!-- Manga Details -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-6 text-xl font-bold text-slate-900">Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-500">Status</label>
                                <span
                                    :class="[
                                        'inline-block rounded-full px-3 py-1 text-sm font-medium',
                                        komik.info?.Status === 'Completed' ? 'bg-yellow-100 text-slate-700' : 'bg-slate-100 text-slate-700',
                                    ]"
                                >
                                    {{ komik.info?.Status || 'Ongoing' }}
                                </span>
                            </div>

                            <div v-if="komik.genres">
                                <label class="mb-2 block text-sm font-medium text-slate-500">Genres</label>
                                <div class="flex flex-wrap gap-2">
                                    <template v-if="typeof komik.genres === 'string'">
                                        <span
                                            v-for="genre in komik.genres.split(',')"
                                            :key="genre"
                                            class="cursor-pointer rounded-full bg-yellow-50 px-3 py-1 text-sm text-slate-700 transition-colors hover:bg-yellow-100"
                                        >
                                            {{ genre.trim() }}
                                        </span>
                                    </template>
                                    <template v-else-if="Array.isArray(komik.genres)">
                                        <span
                                            v-for="genre in komik.genres"
                                            :key="genre"
                                            class="cursor-pointer rounded-full bg-yellow-50 px-3 py-1 text-sm text-slate-700 transition-colors hover:bg-yellow-100"
                                        >
                                            {{ genre }}
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-500">Total Chapters</label>
                                <p class="text-slate-900">{{ komik.chapters?.length || 0 }}</p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-500">Last Updated</label>
                                <p class="text-slate-900">{{ komik.chapters?.[0]?.date || 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reading Progress -->
                    <div class="rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-6 text-white">
                        <h3 class="mb-4 text-lg font-bold">Your Progress</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-300">Chapters Read</span>
                                <span class="font-semibold text-yellow-400">0 / {{ komik.chapters?.length || 0 }}</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-slate-700">
                                <div class="h-2 rounded-full bg-yellow-400" style="width: 0%"></div>
                            </div>
                            <button class="w-full rounded-lg bg-yellow-500 py-2 font-medium text-slate-900 transition-colors hover:bg-yellow-400">
                                Start Reading
                            </button>
                        </div>
                    </div>

                    <!-- Related Tags -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-bold text-slate-900">You might also like</h3>
                        <div class="space-y-3">
                            <div class="flex cursor-pointer items-center gap-3 rounded-lg p-3 transition-colors hover:bg-yellow-50">
                                <div class="h-16 w-12 flex-shrink-0 rounded-lg bg-slate-200"></div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-slate-900">Similar Manga Title</h4>
                                    <p class="text-xs text-slate-500">Romance, Drama</p>
                                </div>
                            </div>
                            <div class="flex cursor-pointer items-center gap-3 rounded-lg p-3 transition-colors hover:bg-yellow-50">
                                <div class="h-16 w-12 flex-shrink-0 rounded-lg bg-slate-200"></div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-slate-900">Another Great Manga</h4>
                                    <p class="text-xs text-slate-500">Action, Adventure</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    max-width: none;
}

.prose p {
    margin-bottom: 1em;
}
</style>
