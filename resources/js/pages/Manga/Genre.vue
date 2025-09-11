<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    title: String,
    items: Array,
    slug: String,
    currentPage: Number,
    hasNextPage: Boolean,
    nextPageUrl: String,
    description: {
        type: String,
        default: '',
    },
    totalItems: {
        type: Number,
        default: 0,
    },
});

// Reactive filter states
const sortBy = ref('latest');
const searchQuery = ref('');
const statusFilter = ref('all');

// Computed filtered items
const filteredItems = computed(() => {
    let filtered = [...props.items];

    // Apply search filter
    if (searchQuery.value) {
        filtered = filtered.filter((item) => item.title.toLowerCase().includes(searchQuery.value.toLowerCase()));
    }

    // Apply status filter
    if (statusFilter.value !== 'all') {
        filtered = filtered.filter((item) => item.status?.toLowerCase() === statusFilter.value);
    }

    // Apply sorting
    switch (sortBy.value) {
        case 'popular':
            filtered.sort((a, b) => (b.popularity || 0) - (a.popularity || 0));
            break;
        case 'rating':
            filtered.sort((a, b) => (b.rating || 0) - (a.rating || 0));
            break;
        case 'a-z':
            filtered.sort((a, b) => a.title.localeCompare(b.title));
            break;
        default: // latest
            break;
    }

    return filtered;
});

const breadcrumbItems = computed(() => [
    { name: 'Home', href: '/' },
    { name: 'Genres', href: '/genres' },
    { name: props.title, href: null },
]);

const genreIcon = computed(() => {
    const iconMap = {
        action: 'fas fa-fist-raised',
        comedy: 'fas fa-laugh',
        romance: 'fas fa-heart',
        drama: 'fas fa-theater-masks',
        fantasy: 'fas fa-magic',
        horror: 'fas fa-skull',
        mystery: 'fas fa-question-circle',
        thriller: 'fas fa-eye',
        adventure: 'fas fa-mountain',
        slice_of_life: 'fas fa-coffee',
    };
    return iconMap[props.slug] || 'fas fa-book';
});

const previousPageUrl = computed(() => {
    if (props.currentPage <= 1) return null;
    if (props.currentPage === 2) {
        return `/genre/${props.slug}`;
    }
    return `/genre/${props.slug}/page/${props.currentPage - 1}`;
});

// Sort options
const sortOptions = [
    { value: 'latest', label: 'Latest Updates' },
    { value: 'popular', label: 'Most Popular' },
    { value: 'rating', label: 'Highest Rated' },
    { value: 'a-z', label: 'A-Z' },
];

const statusOptions = [
    { value: 'all', label: 'All Status' },
    { value: 'ongoing', label: 'Ongoing' },
    { value: 'completed', label: 'Completed' },
    { value: 'hiatus', label: 'On Hiatus' },
];

// Helper functions for manga cards
const getStatusColor = (status) => {
    switch (status?.toLowerCase()) {
        case 'ongoing':
            return 'bg-emerald-100 text-emerald-800 border-emerald-200';
        case 'completed':
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'hiatus':
            return 'bg-amber-100 text-amber-800 border-amber-200';
        case 'cancelled':
            return 'bg-red-100 text-red-800 border-red-200';
        default:
            return 'bg-slate-100 text-slate-800 border-slate-200';
    }
};

const genreColors = [
    'bg-yellow-100 text-yellow-800 border-yellow-200',
    'bg-amber-100 text-amber-800 border-amber-200',
    'bg-orange-100 text-orange-800 border-orange-200',
    'bg-navy-100 text-navy-800 border-navy-200',
    'bg-indigo-100 text-indigo-800 border-indigo-200',
];

const getGenreColor = (index) => {
    return genreColors[index % genreColors.length];
};

const getDisplayGenres = (genre) => {
    if (!genre) return [];
    const genres = Array.isArray(genre) ? genre : genre.split(',').map((g) => g.trim());
    return genres.slice(0, 3);
};

const getRating = (rating) => {
    return rating ? Number(rating).toFixed(1) : '0.0';
};

const getPageUrl = (page) => {
    if (page === 1) {
        return `/genre/${props.slug}`;
    }
    return `/genre/${props.slug}/page/${page}`;
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
        <!-- Header Section -->
        <div class="border-b bg-gradient-to-r from-slate-800 to-slate-900 shadow-lg">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav aria-label="Breadcrumb" class="mb-4">
                    <ol class="flex items-center space-x-2 text-sm">
                        <li v-for="(item, index) in breadcrumbItems" :key="index" class="flex items-center">
                            <!-- Separator -->
                            <i v-if="index > 0" class="fas fa-chevron-right mx-2 text-xs text-slate-400"></i>

                            <!-- Breadcrumb Item -->
                            <div class="flex items-center">
                                <!-- Home Icon for first item -->
                                <i v-if="index === 0" class="fas fa-home mr-2 text-slate-300"></i>

                                <!-- Link or Current Page -->
                                <a
                                    v-if="item.href && index < breadcrumbItems.length - 1"
                                    :href="item.href"
                                    class="text-slate-300 transition-colors duration-200 hover:text-yellow-400"
                                >
                                    {{ item.name }}
                                </a>
                                <span v-else :class="[index === breadcrumbItems.length - 1 ? 'font-medium text-yellow-400' : 'text-slate-300']">
                                    {{ item.name }}
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Genre Title -->
                <div>
                    <div class="mb-3 flex items-center gap-3">
                        <i :class="genreIcon" class="text-2xl text-yellow-400"></i>
                        <h1 class="text-3xl font-bold text-white">{{ title }}</h1>
                    </div>
                    <p v-if="description" class="max-w-2xl text-slate-300">{{ description }}</p>
                    <div class="mt-2 text-sm text-slate-400">{{ totalItems || items.length }} manga found</div>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8 lg:flex-row">
                <!-- Main Content -->
                <main class="flex-1">
                    <!-- Filter Bar -->
                    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row">
                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-slate-400"></i>
                                </div>
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search manga titles..."
                                    class="w-full rounded-lg border border-slate-300 py-3 pr-4 pl-10 transition-all duration-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 focus:outline-none"
                                />
                            </div>

                            <!-- Sort Dropdown -->
                            <div class="min-w-[180px]">
                                <div class="relative">
                                    <select
                                        v-model="sortBy"
                                        class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-3 pr-8 transition-all duration-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 focus:outline-none"
                                    >
                                        <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                        <i class="fas fa-chevron-down text-sm text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="min-w-[140px]">
                                <div class="relative">
                                    <select
                                        v-model="statusFilter"
                                        class="w-full appearance-none rounded-lg border border-slate-300 bg-white px-4 py-3 pr-8 transition-all duration-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 focus:outline-none"
                                    >
                                        <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                                        <i class="fas fa-chevron-down text-sm text-slate-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Clear Filters Button -->
                            <button
                                @click="
                                    searchQuery = '';
                                    statusFilter = 'all';
                                "
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-3 text-slate-600 transition-colors duration-200 hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700"
                            >
                                <i class="fas fa-times"></i>
                                <span class="hidden sm:inline">Clear</span>
                            </button>
                        </div>

                        <!-- Quick Filters (Mobile) -->
                        <div class="mt-4 flex flex-wrap gap-2 lg:hidden">
                            <button
                                v-for="option in sortOptions"
                                :key="option.value"
                                @click="sortBy = option.value"
                                :class="[
                                    'rounded-lg border px-3 py-2 text-sm font-medium transition-colors duration-200',
                                    sortBy === option.value
                                        ? 'border-yellow-500 bg-yellow-400 text-slate-800'
                                        : 'border-slate-300 bg-white text-slate-600 hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700',
                                ]"
                            >
                                {{ option.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Manga Grid -->
                    <div v-if="filteredItems.length > 0">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
                            <!-- Manga Card -->
                            <article
                                v-for="manga in filteredItems"
                                :key="manga.slug"
                                class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-yellow-300 hover:shadow-lg"
                            >
                                <!-- Image Container -->
                                <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                                    <img
                                        :src="manga.thumbnail"
                                        :alt="manga.title"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    />

                                    <!-- Status Badge -->
                                    <div class="absolute top-2 left-2">
                                        <span :class="getStatusColor(manga.status)" class="rounded-full border px-2 py-1 text-xs font-medium">
                                            {{ manga.status || 'Unknown' }}
                                        </span>
                                    </div>

                                    <!-- Rating Badge -->
                                    <div
                                        class="absolute top-2 right-2 flex items-center gap-1 rounded-full bg-slate-800/80 px-2 py-1 text-xs font-medium text-yellow-400 backdrop-blur-sm"
                                    >
                                        <i class="fas fa-star"></i>
                                        {{ getRating(manga.rating) }}
                                    </div>

                                    <!-- Hover Overlay -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-slate-800/20 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                    >
                                        <div class="rounded-lg bg-yellow-400 px-4 py-2 font-medium text-slate-800 shadow-lg">
                                            <i class="fas fa-eye mr-2"></i>
                                            <span>Read Now</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <!-- Title -->
                                    <h3 class="mb-2 line-clamp-2 font-semibold text-slate-900 transition-colors hover:text-yellow-600">
                                        <a :href="`/manga/${manga.slug}`" class="block">
                                            {{ manga.title }}
                                        </a>
                                    </h3>

                                    <!-- Type & Latest Chapter -->
                                    <div class="mb-3 flex items-center justify-between text-sm text-slate-600">
                                        <span class="capitalize">{{ manga.type || 'Manga' }}</span>
                                        <span v-if="manga.latest_chapter" class="font-medium">Ch. {{ manga.latest_chapter }}</span>
                                    </div>

                                    <!-- Genre Tags -->
                                    <div v-if="getDisplayGenres(manga.genre).length > 0" class="mb-3 flex flex-wrap gap-1">
                                        <span
                                            v-for="(genre, index) in getDisplayGenres(manga.genre)"
                                            :key="genre"
                                            :class="getGenreColor(index)"
                                            class="rounded-full border px-2 py-1 text-xs font-medium"
                                        >
                                            {{ genre }}
                                        </span>
                                    </div>

                                    <!-- Action Button -->
                                    <a
                                        :href="`/manga/${manga.slug}`"
                                        class="group flex w-full transform items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-yellow-400 to-yellow-500 px-4 py-2 text-sm font-medium text-slate-800 transition-all duration-200 hover:scale-[1.02] hover:from-yellow-500 hover:to-yellow-600 hover:shadow-md"
                                    >
                                        <i class="fas fa-book-open transition-transform group-hover:scale-110"></i>
                                        Read Manga
                                    </a>
                                </div>
                            </article>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12 flex flex-col items-center justify-between gap-4 sm:flex-row">
                            <!-- Page Info -->
                            <div class="text-sm text-slate-600">
                                <span>Page {{ currentPage }}</span>
                            </div>

                            <!-- Navigation -->
                            <nav class="flex items-center gap-2">
                                <!-- Previous Page -->
                                <a
                                    v-if="previousPageUrl"
                                    :href="previousPageUrl"
                                    class="flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-all duration-200 hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700"
                                >
                                    <i class="fas fa-chevron-left text-xs"></i>
                                    <span class="hidden sm:inline">Previous</span>
                                </a>
                                <button
                                    v-else
                                    disabled
                                    class="flex cursor-not-allowed items-center gap-2 rounded-lg border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-medium text-slate-400"
                                >
                                    <i class="fas fa-chevron-left text-xs"></i>
                                    <span class="hidden sm:inline">Previous</span>
                                </button>

                                <!-- Page Number -->
                                <span class="rounded-lg border border-yellow-500 bg-yellow-400 px-3 py-2 text-sm font-medium text-slate-800">
                                    {{ currentPage }}
                                </span>

                                <!-- Next Page -->
                                <a
                                    v-if="hasNextPage && nextPageUrl"
                                    :href="nextPageUrl"
                                    class="flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-all duration-200 hover:border-yellow-300 hover:bg-yellow-50 hover:text-yellow-700"
                                >
                                    <span class="hidden sm:inline">Next</span>
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </a>
                                <button
                                    v-else
                                    disabled
                                    class="flex cursor-not-allowed items-center gap-2 rounded-lg border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-medium text-slate-400"
                                >
                                    <span class="hidden sm:inline">Next</span>
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </nav>

                            <!-- Load More Button -->
                            <div class="w-full sm:w-auto">
                                <a
                                    v-if="hasNextPage && nextPageUrl"
                                    :href="nextPageUrl"
                                    class="inline-flex w-full transform items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-3 font-medium text-yellow-400 transition-all duration-200 hover:scale-[1.02] hover:from-slate-700 hover:to-slate-800 hover:shadow-lg sm:w-auto"
                                >
                                    <i class="fas fa-plus text-sm"></i>
                                    Load More Manga
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="py-16 text-center">
                        <i class="fas fa-search mb-4 text-6xl text-slate-300"></i>
                        <h3 class="mb-2 text-xl font-semibold text-slate-600">No manga found</h3>
                        <p class="text-slate-500">Try adjusting your search or filter criteria</p>
                    </div>
                </main>

                <!-- Sidebar (Desktop Only) -->
                <aside class="hidden w-80 lg:block">
                    <div class="sticky top-8 space-y-6">
                        <!-- Popular in Genre -->
                        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="mb-4 flex items-center gap-2 font-semibold text-slate-900">
                                <i class="fas fa-fire text-yellow-500"></i>
                                Popular in {{ title }}
                            </h3>
                            <div class="space-y-3">
                                <div
                                    v-for="item in items.slice(0, 5)"
                                    :key="item.slug"
                                    class="flex gap-3 rounded-lg border border-transparent p-2 transition-colors hover:border-yellow-200 hover:bg-yellow-50"
                                >
                                    <img :src="item.thumbnail" :alt="item.title" class="h-16 w-12 rounded-md object-cover shadow-sm" />
                                    <div class="min-w-0 flex-1">
                                        <h4 class="truncate text-sm font-medium text-slate-900 hover:text-yellow-600">{{ item.title }}</h4>
                                        <div class="mt-1 flex items-center gap-2">
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-star text-xs text-yellow-500"></i>
                                                <span class="text-xs text-slate-600">{{ getRating(item.rating) }}</span>
                                            </div>
                                            <span class="text-xs text-slate-400">•</span>
                                            <span class="text-xs text-slate-600">Ch. {{ item.latest_chapter || 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="rounded-xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6 shadow-sm">
                            <h3 class="mb-4 font-semibold text-yellow-400">Quick Stats</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-slate-300">Total Manga</span>
                                    <span class="font-semibold text-yellow-400">{{ totalItems || items.length }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-300">Current Page</span>
                                    <span class="font-semibold text-yellow-400">{{ currentPage }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-300">Showing</span>
                                    <span class="font-semibold text-yellow-400">{{ filteredItems.length }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Text line clamping */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #fbbf24;
}

/* Custom select styling */
select {
    background-image: none;
}

select::-ms-expand {
    display: none;
}

/* Backdrop blur fallback */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Custom navy color utilities if not in Tailwind config */
.bg-navy-100 {
    background-color: rgb(241 245 249);
}
.text-navy-800 {
    color: rgb(30 41 59);
}
.border-navy-200 {
    border-color: rgb(226 232 240);
}
</style>
