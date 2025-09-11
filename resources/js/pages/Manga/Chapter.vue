<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    chapter: Object,
});

// Reactive state
const imageLoadStates = ref({});
const showNavigation = ref(false);
const showFloatingMenu = ref(false);
const readingProgress = ref(0);
const isDarkMode = ref(false); // Changed from currentTheme to isDarkMode for consistency

// Initialize component
onMounted(() => {
    if (props.chapter?.images) {
        props.chapter.images.forEach((img) => {
            imageLoadStates.value[img.id] = 'loading';
        });
    }

    // Setup scroll listener for progress
    window.addEventListener('scroll', updateProgress);
    window.addEventListener('keydown', handleKeydown);

    // Load saved theme preference from localStorage
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    isDarkMode.value = savedDarkMode;
    if (savedDarkMode) {
        document.documentElement.classList.add('dark');
    }

    updateProgress();
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
    window.removeEventListener('keydown', handleKeydown);
});

// Computed properties
const totalPages = computed(() => props.chapter?.images?.length || 0);

// Methods
const handleImageLoad = (imageId) => {
    imageLoadStates.value[imageId] = 'loaded';
};

const handleImageError = (imageId) => {
    imageLoadStates.value[imageId] = 'error';
};

const retryImage = (imageId) => {
    imageLoadStates.value[imageId] = 'loading';
    const img = document.querySelector(`img[data-image-id="${imageId}"]`);
    if (img) {
        const src = img.src;
        img.src = '';
        setTimeout(() => {
            img.src = src;
        }, 100);
    }
};

const updateProgress = () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrollPercent = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
    readingProgress.value = Math.round(scrollPercent);
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    showFloatingMenu.value = false;
};

const scrollToBottom = () => {
    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    showFloatingMenu.value = false;
};

const shareChapter = () => {
    if (navigator.share) {
        navigator.share({
            title: props.chapter.title,
            text: `Reading ${props.chapter.chapterInfo?.Judul}`,
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
    showFloatingMenu.value = false;
};

// Dark mode toggle function
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', isDarkMode.value);
};

const handleKeydown = (event) => {
    if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
        return;
    }

    switch (event.key) {
        case 'ArrowLeft':
        case 'a':
        case 'A':
            if (props.chapter?.navigation?.prevChapter) {
                window.location.href = `/manga/${props.chapter.navigation.prevChapter.slug}/${props.chapter.navigation.prevChapter.chapter}`;
            }
            break;

        case 'ArrowRight':
        case 'd':
        case 'D':
            if (props.chapter?.navigation?.nextChapter) {
                window.location.href = `/manga/${props.chapter.navigation.nextChapter.slug}/${props.chapter.navigation.nextChapter.chapter}`;
            }
            break;

        case 'h':
        case 'H':
            if (props.chapter?.navigation?.allChapters) {
                window.location.href = props.chapter.navigation.allChapters;
            }
            break;

        case ' ':
            event.preventDefault();
            if (event.shiftKey) {
                window.scrollBy(0, -window.innerHeight * 0.8);
            } else {
                window.scrollBy(0, window.innerHeight * 0.8);
            }
            break;
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 transition-colors duration-300 dark:bg-gray-900">
        <!-- Reading Progress Bar -->
        <div class="fixed top-0 left-0 z-50 h-1 w-full bg-gray-200 dark:bg-gray-800">
            <div class="h-full bg-yellow-500 transition-all duration-150 ease-out dark:bg-yellow-400" :style="{ width: `${readingProgress}%` }"></div>
        </div>

        <!-- Header Navigation -->
        <header class="sticky top-0 z-40 border-b border-gray-200 bg-gray-50/95 backdrop-blur-sm dark:border-gray-700 dark:bg-gray-900/95">
            <div class="mx-auto max-w-6xl px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a
                            v-if="props.chapter.navigation?.allChapters"
                            :href="props.chapter.navigation.allChapters"
                            class="group flex items-center space-x-2 text-gray-600 transition-colors hover:text-gray-800 dark:text-gray-300 dark:hover:text-yellow-400"
                        >
                            <svg
                                class="h-5 w-5 transition-transform group-hover:-translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden font-medium sm:inline">Back to Manga</span>
                        </a>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button
                            @click="showNavigation = !showNavigation"
                            class="rounded-lg bg-gray-100 p-2 transition-all duration-200 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                            :class="{ 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-yellow-300': showNavigation }"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <button
                            @click="toggleDarkMode"
                            class="rounded-lg bg-gray-100 p-2 transition-all duration-200 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                        >
                            <svg v-if="!isDarkMode" class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                                ></path>
                            </svg>
                            <svg v-else class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                                ></path>
                            </svg>
                        </button>

                        <button
                            @click="shareChapter"
                            class="rounded-lg bg-gray-100 p-2 transition-all duration-200 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Chapter Info Section -->
        <div class="mx-auto max-w-4xl px-4 py-6">
            <div class="mb-6 bg-white p-6 dark:bg-gray-800">
                <div class="space-y-4 text-center">
                    <h1 class="text-2xl leading-tight font-bold text-gray-900 sm:text-3xl dark:text-white">
                        {{ props.chapter.title }}
                    </h1>

                    <div
                        v-if="props.chapter.chapterInfo?.Judul"
                        class="inline-flex items-center rounded-full bg-yellow-100 px-4 py-2 dark:bg-yellow-900/30"
                    >
                        <span class="font-semibold text-gray-800 dark:text-yellow-200">
                            {{ props.chapter.chapterInfo.Judul }}
                        </span>
                    </div>

                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-600 dark:text-gray-300">
                        <div v-if="props.chapter.chapterInfo?.['Tanggal Rilis']" class="flex items-center space-x-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <span>{{ props.chapter.chapterInfo['Tanggal Rilis'] }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                ></path>
                            </svg>
                            <span>{{ totalPages }} pages</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="h-2 w-2 animate-pulse rounded-full bg-yellow-500 dark:bg-yellow-400"></div>
                            <span>{{ readingProgress }}% complete</span>
                        </div>
                    </div>

                    <p v-if="props.chapter.description" class="mt-4 leading-relaxed text-gray-600 dark:text-gray-300">
                        {{ props.chapter.description }}
                    </p>
                </div>
            </div>

            <!-- Chapter Navigation Top -->
            <div class="mb-8 flex justify-center">
                <div class="flex items-center space-x-4 bg-white p-4 dark:bg-gray-800">
                    <a
                        v-if="props.chapter.navigation?.prevChapter"
                        :href="`/manga/${props.chapter.navigation.prevChapter.slug}/${props.chapter.navigation.prevChapter.chapter}`"
                        class="group flex items-center space-x-2 rounded-xl bg-gray-100 px-6 py-3 transition-all duration-200 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                    >
                        <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="font-medium">Previous</span>
                    </a>

                    <a
                        v-if="props.chapter.navigation?.allChapters"
                        :href="props.chapter.navigation.allChapters"
                        class="flex items-center space-x-2 rounded-xl bg-yellow-200 px-6 py-3 text-gray-800 transition-all duration-200 hover:bg-yellow-300 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-yellow-900/30 dark:text-yellow-200 dark:hover:bg-yellow-900/40 dark:focus:ring-yellow-400"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="font-medium">Semua Chapter</span>
                    </a>

                    <a
                        v-if="props.chapter.navigation?.nextChapter"
                        :href="`/manga/${props.chapter.navigation.nextChapter.slug}/${props.chapter.navigation.nextChapter.chapter}`"
                        class="group flex items-center space-x-2 rounded-xl bg-gray-100 px-6 py-3 transition-all duration-200 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                    >
                        <span class="font-medium">Next</span>
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Reader Interface -->
        <div class="mx-auto max-w-4xl px-4">
            <div v-if="props.chapter.images && props.chapter.images.length" class="space-y-0">
                <div v-for="(img, index) in props.chapter.images" :key="img.id" class="group relative">
                    <div class="overflow-hidden bg-white dark:bg-gray-800">
                        <!-- Loading State -->
                        <div
                            v-if="imageLoadStates[img.id] === 'loading' || !imageLoadStates[img.id]"
                            class="flex h-96 items-center justify-center bg-gray-100 dark:bg-gray-800"
                        >
                            <div class="flex flex-col items-center space-y-4">
                                <div
                                    class="h-10 w-10 animate-spin rounded-full border-4 border-gray-300 border-t-gray-600 dark:border-gray-600 dark:border-t-gray-400"
                                ></div>
                                <div class="space-y-1 text-center">
                                    <div class="text-sm font-medium text-gray-600 dark:text-gray-300">Loading page {{ index + 1 }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ index + 1 }} of {{ totalPages }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <img
                            :src="img.src"
                            :alt="img.alt"
                            :data-image-id="img.id"
                            :class="`h-auto w-full transition-all duration-500 ${
                                imageLoadStates[img.id] === 'loaded' ? 'opacity-100' : 'absolute opacity-0'
                            }`"
                            style="image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges"
                            @load="handleImageLoad(img.id)"
                            @error="handleImageError(img.id)"
                        />

                        <!-- Error State -->
                        <div v-if="imageLoadStates[img.id] === 'error'" class="flex h-96 items-center justify-center bg-red-100 dark:bg-red-900/20">
                            <div class="space-y-4 text-center">
                                <div class="text-4xl">⚠️</div>
                                <div class="space-y-2">
                                    <div class="font-medium text-red-600 dark:text-red-400">Failed to load page {{ index + 1 }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Please check your connection and try again</div>
                                </div>
                                <button
                                    @click="retryImage(img.id)"
                                    class="inline-flex items-center space-x-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        ></path>
                                    </svg>
                                    <span>Retry Loading</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Page Info Overlay -->
                    <div
                        class="absolute top-4 right-4 rounded-lg bg-black/70 px-3 py-2 text-sm font-medium text-white opacity-0 backdrop-blur-sm transition-all duration-200 group-hover:opacity-100"
                    >
                        <div class="flex items-center space-x-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <span>{{ index + 1 }} / {{ totalPages }}</span>
                        </div>
                    </div>

                    <!-- Navigation Hints -->
                    <div
                        class="pointer-events-none absolute inset-0 flex items-center justify-between px-8 opacity-0 transition-opacity group-hover:opacity-100"
                    >
                        <div v-if="index > 0" class="rounded-lg bg-black/50 px-3 py-2 text-sm text-white backdrop-blur-sm">← Previous</div>
                        <div v-if="index < totalPages - 1" class="rounded-lg bg-black/50 px-3 py-2 text-sm text-white backdrop-blur-sm">Next →</div>
                    </div>
                </div>
            </div>

            <!-- No Images State -->
            <div v-else class="mb-8 bg-white p-16 text-center dark:bg-gray-800">
                <div class="space-y-4">
                    <div class="text-6xl opacity-50">📚</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">No Images Available</h3>
                        <p class="text-gray-500 dark:text-gray-300">Gambar tidak tersedia untuk chapter ini.</p>
                    </div>
                    <button
                        @click="window.location.reload()"
                        class="inline-flex items-center space-x-2 rounded-xl bg-yellow-600 px-6 py-3 font-medium text-white transition-colors hover:bg-yellow-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-400"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            ></path>
                        </svg>
                        <span>Reload Page</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="mx-auto max-w-4xl px-4 pb-8">
            <div class="mt-8 bg-white p-6 dark:bg-gray-800">
                <div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
                    <a
                        v-if="chapter.navigation?.prevChapter"
                        :href="`/manga/${chapter.navigation.prevChapter.slug}/${chapter.navigation.prevChapter.chapter}`"
                        class="flex w-full items-center justify-center space-x-3 rounded-xl bg-gray-100 px-6 py-4 transition-all duration-300 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none sm:w-auto sm:justify-start dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                    >
                        <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="font-medium">Previous</span>
                    </a>

                    <a
                        v-if="chapter.navigation?.allChapters"
                        :href="chapter.navigation.allChapters"
                        class="flex w-full items-center justify-center space-x-2 rounded-xl bg-yellow-200 px-6 py-4 text-gray-800 transition-all duration-300 hover:bg-yellow-300 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none sm:w-auto dark:bg-yellow-900/30 dark:text-yellow-200 dark:hover:bg-yellow-900/40 dark:focus:ring-yellow-400"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="font-medium">Semua Chapter</span>
                    </a>

                    <a
                        v-if="chapter.navigation?.nextChapter"
                        :href="`/manga/${chapter.navigation.nextChapter.slug}/${chapter.navigation.nextChapter.chapter}`"
                        class="group flex w-full items-center justify-center space-x-2 rounded-xl bg-gray-100 px-6 py-4 transition-all duration-300 hover:bg-gray-200 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:outline-none sm:w-auto sm:justify-end dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-yellow-400"
                    >
                        <span class="font-medium">Next</span>
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="css">
/* Custom animations */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
