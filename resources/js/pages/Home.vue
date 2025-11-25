<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import MangaCardHorizontal from '@/components/MangaCardHorizontal.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

interface MangaItem {
    mangaSlug: string;
    title: string;
    thumbnail: string;
    latestChapter?: string;
    latestChapterTitle?: string;
    genre?: string;
    updateTime?: string;
    updateCountText?: string;
    type?: string;
    isColored?: boolean;
    rating?: number;
    status?: string;
    readers?: string;
}

interface PopularSection {
    title: string;
    items: MangaItem[];
}

interface PopularData {
    manga: PopularSection;
    manhwa: PopularSection;
    manhua: PopularSection;
}

interface Genre {
    slug: string;
    title: string;
}

defineProps<{
    mangaPopuler?: MangaItem[];
    manhwaPopuler?: MangaItem[];
    manhuaPopuler?: MangaItem[];
    popular?: PopularData;
    genres: Genre[];
    terbaru: MangaItem[];
}>();

const searchQuery = ref('');

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        // Implement search functionality
        console.log('Searching for:', searchQuery.value);
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-900">
        <AppHeader />

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gray-900 text-white">
            <!-- Background Image with Overlay -->
            <div
                v-if="terbaru && terbaru.length > 0"
                class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                :style="`background-image: url('${terbaru[0]?.thumbnail}')`"
            >
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/80 to-gray-900/60"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-transparent to-gray-900/50"></div>
            </div>

            <div class="relative z-10 container mx-auto px-4 py-12 md:py-20">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="mb-2 text-2xl font-bold md:text-3xl">Popular New Titles</h2>
                    <div class="h-1 w-20 rounded bg-orange-500"></div>
                </div>

                <!-- Featured Content -->
                <div v-if="terbaru && terbaru.length > 0" class="grid items-center gap-8 lg:grid-cols-2">
                    <!-- Left: Manga Info -->
                    <div class="space-y-6">
                        <div class="mb-4 flex flex-wrap items-center gap-3">
                            <span class="rounded-full bg-orange-500 px-3 py-1 text-xs font-semibold tracking-wider text-white uppercase">
                                Suggestive
                            </span>
                            <span class="rounded-full bg-gray-600 px-3 py-1 text-xs font-semibold text-white uppercase"> Action </span>
                            <span class="rounded-full bg-gray-600 px-3 py-1 text-xs font-semibold text-white uppercase"> Comedy </span>
                        </div>

                        <h1 class="text-3xl leading-tight font-bold md:text-5xl">Isshou Senkin</h1>

                        <p class="max-w-2xl text-lg leading-relaxed text-gray-300">
                            Setelah pensiun dari pertarungan publik dan dipecat dari pertarungan bawah tanah, semangat hidup Tenma Nozomi yang berusia
                            27 tahun perlahan memudar.
                        </p>

                        <div class="space-y-3">
                            <p class="text-sm text-gray-400">Additional Links:</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="#" class="text-sm font-medium text-orange-400 underline transition-colors hover:text-orange-300">
                                    Official X
                                </a>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 pt-4">
                            <Link
                                :href="`/manga/${terbaru[0]?.mangaSlug}`"
                                class="transform rounded-lg bg-[#f97316] px-8 py-3 font-semibold transition-colors duration-200 hover:scale-105 hover:bg-orange-600"
                            >
                                Read Now
                            </Link>
                            <button
                                class="rounded-lg border border-gray-400 px-8 py-3 font-semibold transition-colors duration-200 hover:border-white"
                            >
                                Add to Library
                            </button>
                        </div>
                    </div>

                    <!-- Right: Manga Cover -->
                    <div class="flex justify-center lg:justify-end">
                        <div class="group relative cursor-pointer">
                            <div
                                class="relative transform overflow-hidden rounded-2xl shadow-2xl transition-transform duration-300 group-hover:scale-105"
                            >
                                <div class="aspect-[3/4] w-64 md:w-80">
                                    <img
                                        src="https://i.ebayimg.com/images/g/tmMAAOSwitdnyZdu/s-l1200.jpg"
                                        alt="terbaru"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                ></div>
                            </div>

                            <!-- Chapter Badge -->
                            <div class="absolute -bottom-4 left-4 rounded-full bg-red-600 px-4 py-2 text-sm font-bold text-white shadow-lg">NEW</div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Dots -->
                <!-- <div class="mt-8 flex items-center justify-end gap-4 lg:mt-12">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-white">NO. 2</span>
                        <div class="flex gap-2">
                            <button class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-600 transition-colors hover:bg-gray-500">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                            <button
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-gray-900 transition-colors hover:bg-gray-200"
                            >
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div> -->

                <!-- Search Bar -->
                <!-- <div class="mx-auto mt-12 max-w-2xl">
                    <div class="relative">
                        <Search class="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 transform text-gray-400" />
                        <input
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                            type="text"
                            placeholder="Search manga, manhwa, manhua..."
                            class="w-full rounded-2xl border border-white/20 bg-white/10 py-4 pr-4 pl-12 text-white placeholder-gray-300 backdrop-blur-sm transition-all duration-300 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        />
                        <button
                            @click="handleSearch"
                            class="absolute top-1/2 right-2 -translate-y-1/2 transform rounded-xl bg-blue-600 px-6 py-2 font-medium text-white transition-colors duration-200 hover:bg-blue-700"
                        >
                            Search
                        </button>
                    </div>
                </div> -->
            </div>
        </section>

        <!-- Main Content -->
        <main class="container mx-auto bg-gray-900 px-4 py-12">
            <!-- Latest Updates Section -->
            <section class="mb-16">
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 rounded bg-blue-500"></div>
                        <h2 class="text-2xl font-bold text-white md:text-3xl">Update Terbaru</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-6">
                    <MangaCardHorizontal v-for="item in terbaru" :key="item.mangaSlug" :manga="item" />
                </div>
            </section>

            <!-- Popular Manga Section -->
            <section class="mb-16">
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 rounded bg-green-500"></div>
                        <h2 class="text-2xl font-bold text-white md:text-3xl">Manga Populer</h2>
                    </div>
                    <Link href="#" class="flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                        Lihat Semua
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-6">
                    <MangaCardHorizontal
                        v-for="manga in popular?.manga.items || mangaPopuler || []"
                        :key="manga.mangaSlug"
                        :manga="{ ...manga, type: 'Manga' }"
                    />
                </div>
            </section>

            <!-- Popular Manhwa Section -->
            <section class="mb-16">
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 rounded bg-purple-500"></div>
                        <h2 class="text-2xl font-bold text-white md:text-3xl">Manhwa Populer</h2>
                    </div>
                    <Link href="#" class="flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                        Lihat Semua
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-6">
                    <MangaCardHorizontal
                        v-for="manhwa in popular?.manhwa.items || manhwaPopuler || []"
                        :key="manhwa.mangaSlug"
                        :manga="{ ...manhwa, type: 'Manhwa' }"
                    />
                </div>
            </section>

            <!-- Popular Manhua Section -->
            <section class="mb-16">
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 rounded bg-orange-500"></div>
                        <h2 class="text-2xl font-bold text-white md:text-3xl">Manhua Populer</h2>
                    </div>
                    <Link href="#" class="flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                        Lihat Semua
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </Link>
                </div>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:grid-cols-6">
                    <MangaCardHorizontal
                        v-for="manhua in popular?.manhua.items || manhuaPopuler || []"
                        :key="manhua.mangaSlug"
                        :manga="{ ...manhua, type: 'Manhua' }"
                    />
                </div>
            </section>

            <!-- Genres Section -->
            <section class="mb-16">
                <div class="mb-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 rounded bg-gradient-to-b from-blue-500 to-purple-600"></div>
                        <h2 class="text-2xl font-bold text-white md:text-3xl">Browse by Genre</h2>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <Link
                        v-for="genre in genres"
                        :key="genre.slug"
                        :href="`/genre/${genre.slug}`"
                        class="inline-flex transform items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:scale-105 hover:border-transparent hover:bg-gradient-to-r hover:from-blue-500 hover:to-purple-600 hover:text-white hover:shadow-md"
                    >
                        {{ genre.title }}
                    </Link>
                </div>
            </section>
        </main>
    </div>
</template>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
