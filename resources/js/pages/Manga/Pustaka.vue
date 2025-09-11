<script setup>
defineProps({
    pustaka: Object,
});
</script>

<template>
    <div class="dark:from-navy-900 dark:to-navy-800 min-h-screen bg-gradient-to-br from-yellow-50 to-amber-50">
        <!-- Header Section -->
        <div class="border-navy-200 bg-navy-800/90 dark:border-navy-700 dark:bg-navy-900/90 sticky top-0 z-40 border-b backdrop-blur-lg">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-yellow-500 to-amber-500">
                            <i class="fas fa-book-open text-navy-900 text-lg"></i>
                        </div>
                        <h1 class="bg-gradient-to-r from-yellow-400 to-amber-300 bg-clip-text text-3xl font-bold text-transparent">Pustaka Manga</h1>
                    </div>

                    <!-- Search & Filters -->
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <div class="relative">
                            <i class="fas fa-search text-navy-400 absolute top-1/2 left-3 -translate-y-1/2 transform"></i>
                            <input
                                type="text"
                                placeholder="Cari manga..."
                                class="border-navy-300 dark:border-navy-600 dark:bg-navy-700 w-full rounded-xl border bg-white py-2.5 pr-4 pl-10 transition-all duration-200 focus:border-transparent focus:ring-2 focus:ring-yellow-500 sm:w-64 dark:text-white"
                            />
                        </div>
                        <button
                            class="text-navy-900 flex items-center gap-2 rounded-xl border border-yellow-300 bg-yellow-500 px-4 py-2.5 transition-all duration-200 hover:bg-yellow-400 dark:border-yellow-400 dark:bg-yellow-600 dark:hover:bg-yellow-500"
                        >
                            <i class="fas fa-filter"></i>
                            <span class="font-medium">Filter</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Stats Bar -->
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-navy-600 dark:text-navy-300 flex items-center gap-6 text-sm">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-books text-yellow-600"></i>
                        {{ pustaka.results?.length || 0 }} Manga
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-page text-yellow-600"></i>
                        Halaman {{ pustaka.page || 1 }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-navy-600 dark:text-navy-300 text-sm">Urutkan:</span>
                    <select
                        class="border-navy-200 text-navy-700 dark:border-navy-600 dark:bg-navy-700 dark:text-navy-200 rounded-lg border bg-white px-3 py-1.5 text-sm focus:border-transparent focus:ring-2 focus:ring-yellow-500"
                    >
                        <option>Terbaru</option>
                        <option>Populer</option>
                        <option>Rating</option>
                        <option>A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Manga Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                <div
                    v-for="item in pustaka.results"
                    :key="item.detailUrl"
                    class="group border-navy-100 dark:border-navy-700 dark:bg-navy-800 dark:shadow-navy-900/20 overflow-hidden rounded-2xl border bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-yellow-200/20"
                >
                    <!-- Image Container -->
                    <div class="bg-navy-100 dark:bg-navy-700 relative aspect-[3/4] overflow-hidden">
                        <img
                            :src="item.thumbnail"
                            :alt="item.title"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        />

                        <!-- Overlay -->
                        <div
                            class="from-navy-900/70 absolute inset-0 bg-gradient-to-t via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        >
                            <div class="absolute right-4 bottom-4 left-4">
                                <a
                                    :href="item.detailUrl"
                                    class="text-navy-900 block w-full translate-y-2 transform rounded-lg bg-yellow-500 px-4 py-2 text-center text-sm font-medium transition-all duration-200 group-hover:translate-y-0 hover:bg-yellow-400"
                                >
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="text-navy-900 rounded-full bg-yellow-500 px-2 py-1 text-xs font-medium">
                                {{ item.type }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <h3
                            class="text-navy-800 mb-2 line-clamp-2 text-lg font-bold transition-colors duration-200 group-hover:text-yellow-600 dark:text-white dark:group-hover:text-yellow-400"
                        >
                            {{ item.title }}
                        </h3>

                        <p class="text-navy-600 dark:text-navy-400 mb-3 line-clamp-2 text-sm leading-relaxed">
                            {{ item.description }}
                        </p>

                        <!-- Genre Tags -->
                        <div class="mb-3 flex flex-wrap gap-1">
                            <span
                                v-for="genre in item.genre?.split(',').slice(0, 2)"
                                :key="genre"
                                class="text-navy-700 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium dark:bg-yellow-800 dark:text-yellow-200"
                            >
                                {{ genre?.trim() }}
                            </span>
                            <span
                                v-if="item.genre?.split(',').length > 2"
                                class="bg-navy-100 text-navy-500 dark:bg-navy-700 dark:text-navy-400 rounded-md px-2 py-1 text-xs"
                            >
                                +{{ item.genre.split(',').length - 2 }}
                            </span>
                        </div>

                        <!-- Stats -->
                        <div class="text-navy-500 dark:text-navy-400 mb-3 flex items-center justify-between text-xs">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-chart-bar text-yellow-500"></i>
                                {{ item.stats }}
                            </span>
                            <span class="flex items-center gap-1" v-if="item.rating">
                                <i class="fas fa-star text-yellow-500"></i>
                                {{ item.rating }}
                            </span>
                        </div>

                        <!-- Latest Chapter -->
                        <div v-if="item.latestChapter" class="border-navy-100 dark:border-navy-700 border-t pt-3">
                            <a
                                :href="item.latestChapter.url"
                                class="flex items-center justify-between text-sm text-yellow-600 transition-colors duration-200 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300"
                            >
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-bookmark text-xs"></i>
                                    <span class="truncate">{{ item.latestChapter.title }}</span>
                                </span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!pustaka.results || pustaka.results.length === 0" class="py-16 text-center">
                <div class="dark:bg-navy-800 mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-yellow-100">
                    <i class="fas fa-book-open text-3xl text-yellow-500 dark:text-yellow-400"></i>
                </div>
                <h3 class="text-navy-600 dark:text-navy-400 mb-2 text-xl font-medium">Tidak ada manga ditemukan</h3>
                <p class="text-navy-500 dark:text-navy-500">Coba ubah filter atau kata kunci pencarian</p>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex items-center justify-center gap-4" v-if="pustaka.results && pustaka.results.length">
                <a
                    v-if="pustaka.page > 1"
                    :href="`/pustaka/${pustaka.page - 1}`"
                    class="border-navy-200 text-navy-700 dark:border-navy-700 dark:bg-navy-800 dark:text-navy-300 dark:hover:bg-navy-700 flex items-center gap-2 rounded-xl border bg-white px-6 py-3 font-medium transition-all duration-200 hover:border-yellow-300 hover:bg-yellow-50"
                >
                    <i class="fas fa-chevron-left text-sm"></i>
                    Sebelumnya
                </a>

                <div class="flex items-center gap-2">
                    <span class="text-navy-900 rounded-xl bg-yellow-500 px-4 py-3 font-medium">
                        {{ pustaka.page || 1 }}
                    </span>
                </div>

                <a
                    :href="`/pustaka/${pustaka.page + 1}`"
                    class="border-navy-200 text-navy-700 dark:border-navy-700 dark:bg-navy-800 dark:text-navy-300 dark:hover:bg-navy-700 flex items-center gap-2 rounded-xl border bg-white px-6 py-3 font-medium transition-all duration-200 hover:border-yellow-300 hover:bg-yellow-50"
                >
                    Selanjutnya
                    <i class="fas fa-chevron-right text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference "../../../css/app.css";

/* Custom Navy Colors */
.bg-navy-50 {
    background-color: #f8fafc;
}
.bg-navy-100 {
    background-color: #f1f5f9;
}
.bg-navy-200 {
    background-color: #e2e8f0;
}
.bg-navy-300 {
    background-color: #cbd5e1;
}
.bg-navy-400 {
    background-color: #94a3b8;
}
.bg-navy-500 {
    background-color: #64748b;
}
.bg-navy-600 {
    background-color: #475569;
}
.bg-navy-700 {
    background-color: #334155;
}
.bg-navy-800 {
    background-color: #1e293b;
}
.bg-navy-900 {
    background-color: #0f172a;
}

.text-navy-50 {
    color: #f8fafc;
}
.text-navy-100 {
    color: #f1f5f9;
}
.text-navy-200 {
    color: #e2e8f0;
}
.text-navy-300 {
    color: #cbd5e1;
}
.text-navy-400 {
    color: #94a3b8;
}
.text-navy-500 {
    color: #64748b;
}
.text-navy-600 {
    color: #475569;
}
.text-navy-700 {
    color: #334155;
}
.text-navy-800 {
    color: #1e293b;
}
.text-navy-900 {
    color: #0f172a;
}

.border-navy-100 {
    border-color: #f1f5f9;
}
.border-navy-200 {
    border-color: #e2e8f0;
}
.border-navy-300 {
    border-color: #cbd5e1;
}
.border-navy-600 {
    border-color: #475569;
}
.border-navy-700 {
    border-color: #334155;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar for webkit browsers */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background-color: #f1f5f9;
}

.dark ::-webkit-scrollbar-track {
    background-color: #1e293b;
}

::-webkit-scrollbar-thumb {
    background-color: #fbbf24;
    border-radius: 9999px;
}

::-webkit-scrollbar-thumb:hover {
    background-color: #f59e0b;
}
</style>
