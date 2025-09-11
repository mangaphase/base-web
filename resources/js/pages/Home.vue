<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps({
    mangaPopuler: Array,
    manhwaPopuler: Array,
    manhuaPopuler: Array,
    genres: Array,
    terbaru: Array,
});

const searchQuery = ref('');
const isDarkMode = ref(false);
const isMenuOpen = ref(false);
const currentSlide = ref(0);

// Dark mode toggle
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('darkMode', isDarkMode.value);
};

// Initialize dark mode from localStorage
onMounted(() => {
    const savedDarkMode = localStorage.getItem('darkMode') === 'true';
    isDarkMode.value = savedDarkMode;
    if (savedDarkMode) {
        document.documentElement.classList.add('dark');
    }
});

// Hero slider functionality
const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % 5;
};

const prevSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? 4 : currentSlide.value - 1;
};

// Auto-advance hero slider
onMounted(() => {
    setInterval(nextSlide, 5000);
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 transition-colors duration-300 dark:bg-gray-900">
        <!-- Header -->
        <header class="border-navy-200 bg-navy-900/95 dark:border-navy-700 dark:bg-navy-900/95 sticky top-0 z-50 border-b backdrop-blur-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-4">
                        <Link href="/" class="flex items-center space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-600">
                                <i class="fas fa-book-open text-navy-900 text-sm"></i>
                            </div>
                            <span class="text-xl font-bold text-white dark:text-white">MangaHub</span>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden items-center space-x-8 lg:flex">
                        <Link href="/" class="text-gray-200 transition-colors hover:text-yellow-400 dark:text-gray-300 dark:hover:text-yellow-400">
                            <i class="fas fa-home mr-2"></i>Home
                        </Link>
                        <Link
                            href="/popular"
                            class="text-gray-200 transition-colors hover:text-yellow-400 dark:text-gray-300 dark:hover:text-yellow-400"
                        >
                            <i class="fas fa-fire mr-2"></i>Popular
                        </Link>
                        <Link
                            href="/latest"
                            class="text-gray-200 transition-colors hover:text-yellow-400 dark:text-gray-300 dark:hover:text-yellow-400"
                        >
                            <i class="fas fa-clock mr-2"></i>Latest
                        </Link>
                        <Link
                            href="/genres"
                            class="text-gray-200 transition-colors hover:text-yellow-400 dark:text-gray-300 dark:hover:text-yellow-400"
                        >
                            <i class="fas fa-tags mr-2"></i>Genres
                        </Link>
                    </nav>

                    <!-- Search Bar -->
                    <div class="mx-8 hidden max-w-lg flex-1 items-center md:flex">
                        <div class="relative w-full">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search manga, manhwa, manhua..."
                                class="border-navy-300 text-navy-900 w-full rounded-lg border bg-white py-2 pr-4 pl-10 placeholder-gray-500 transition-all focus:border-transparent focus:ring-2 focus:ring-yellow-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                            />
                        </div>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button
                            @click="toggleDarkMode"
                            class="bg-navy-800 hover:bg-navy-700 rounded-lg p-2 text-gray-200 transition-colors dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                        >
                            <i :class="isDarkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                        </button>

                        <!-- Auth Buttons -->
                        <div v-if="$page.props.auth.user" class="hidden sm:block">
                            <Link
                                :href="route('dashboard')"
                                class="text-navy-900 inline-flex items-center rounded-lg bg-yellow-500 px-4 py-2 font-semibold transition-colors hover:bg-yellow-400"
                            >
                                <i class="fas fa-user mr-2"></i>Dashboard
                            </Link>
                        </div>
                        <div v-else class="hidden items-center space-x-2 sm:flex">
                            <Link
                                :href="route('login')"
                                class="px-4 py-2 text-gray-200 transition-colors hover:text-yellow-400 dark:text-gray-300 dark:hover:text-yellow-400"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="route('register')"
                                class="text-navy-900 rounded-lg bg-yellow-500 px-4 py-2 font-semibold transition-colors hover:bg-yellow-400"
                            >
                                Register
                            </Link>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button
                            @click="isMenuOpen = !isMenuOpen"
                            class="bg-navy-800 rounded-lg p-2 text-gray-200 lg:hidden dark:bg-gray-700 dark:text-gray-300"
                        >
                            <i :class="isMenuOpen ? 'fas fa-times' : 'fas fa-bars'"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div v-show="isMenuOpen" class="border-navy-700 border-t py-4 lg:hidden dark:border-gray-700">
                    <div class="space-y-4">
                        <!-- Mobile Search -->
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search manga..."
                                class="border-navy-300 text-navy-900 w-full rounded-lg border bg-white py-2 pr-4 pl-10 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <!-- Mobile Navigation -->
                        <nav class="space-y-2">
                            <Link href="/" class="flex items-center py-2 text-gray-200 hover:text-yellow-400 dark:text-gray-300">
                                <i class="fas fa-home mr-3 w-4"></i>Home
                            </Link>
                            <Link href="/popular" class="flex items-center py-2 text-gray-200 hover:text-yellow-400 dark:text-gray-300">
                                <i class="fas fa-fire mr-3 w-4"></i>Popular
                            </Link>
                            <Link href="/latest" class="flex items-center py-2 text-gray-200 hover:text-yellow-400 dark:text-gray-300">
                                <i class="fas fa-clock mr-3 w-4"></i>Latest
                            </Link>
                            <Link href="/genres" class="flex items-center py-2 text-gray-200 hover:text-yellow-400 dark:text-gray-300">
                                <i class="fas fa-tags mr-3 w-4"></i>Genres
                            </Link>
                        </nav>

                        <!-- Mobile Auth -->
                        <div v-if="$page.props.auth.user" class="border-navy-700 border-t pt-4 dark:border-gray-700">
                            <Link
                                :href="route('dashboard')"
                                class="text-navy-900 block w-full rounded-lg bg-yellow-500 py-2 text-center font-semibold"
                            >
                                Dashboard
                            </Link>
                        </div>
                        <div v-else class="border-navy-700 space-y-2 border-t pt-4 dark:border-gray-700">
                            <Link :href="route('login')" class="block py-2 text-center text-gray-200 dark:text-gray-300"> Log in </Link>
                            <Link :href="route('register')" class="text-navy-900 block rounded-lg bg-yellow-500 py-2 text-center font-semibold">
                                Register
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="from-navy-800 to-navy-900 relative bg-gradient-to-r py-20 text-white">
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-400/10 to-yellow-600/10"></div>
            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="mb-6 text-4xl font-bold md:text-6xl">Discover Amazing Manga</h1>
                    <p class="mb-8 text-xl opacity-90 md:text-2xl">Read thousands of manga, manhwa, and manhua for free</p>
                    <div class="mx-auto flex max-w-md flex-col justify-center gap-4 sm:flex-row">
                        <button class="text-navy-900 rounded-lg bg-yellow-500 px-8 py-3 font-semibold transition-colors hover:bg-yellow-400">
                            <i class="fas fa-book-open mr-2"></i>Start Reading
                        </button>
                        <button
                            class="hover:text-navy-900 rounded-lg border-2 border-yellow-500 px-8 py-3 font-semibold text-yellow-500 transition-colors hover:bg-yellow-500"
                        >
                            <i class="fas fa-fire mr-2"></i>Popular Now
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <!-- Latest Updates Section -->
            <section class="mb-16">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fas fa-clock mr-3 text-yellow-500"></i>Latest Updates</h2>
                    <Link href="/latest" class="font-medium text-yellow-600 hover:text-yellow-700">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </Link>
                </div>

                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                    <div v-for="manga in terbaru.slice(0, 12)" :key="manga.mangaSlug" class="group">
                        <Link :href="`/manga/${manga.mangaSlug}`" class="block">
                            <div
                                class="relative mb-3 aspect-[3/4] overflow-hidden rounded-lg border-2 border-transparent bg-gray-200 transition-all duration-300 group-hover:border-yellow-400 dark:bg-gray-700"
                            >
                                <img
                                    :src="manga.thumbnail"
                                    :alt="manga.title"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy"
                                />
                                <div class="bg-navy-900/0 group-hover:bg-navy-900/20 absolute inset-0 transition-colors duration-300"></div>
                                <div class="absolute top-2 right-2">
                                    <span
                                        class="text-navy-900 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 px-2 py-1 text-xs font-semibold"
                                    >
                                        {{ manga.latestChapter }}
                                    </span>
                                </div>
                            </div>
                            <h3
                                class="line-clamp-2 text-sm font-semibold text-gray-900 transition-colors group-hover:text-yellow-600 dark:text-white"
                            >
                                {{ manga.title }}
                            </h3>
                        </Link>
                    </div>
                </div>
            </section>

            <!-- Popular Sections -->
            <div class="mb-16 grid gap-12 lg:grid-cols-3">
                <!-- Popular Manga -->
                <section>
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-fire mr-2 text-yellow-500"></i>Popular Manga
                    </h2>
                    <div class="space-y-4">
                        <div
                            v-for="(manga, index) in mangaPopuler.slice(0, 5)"
                            :key="manga.mangaSlug"
                            class="group hover:to-navy-50 flex items-center space-x-4 rounded-lg border border-transparent p-3 transition-colors hover:border-yellow-200 hover:bg-gradient-to-r hover:from-yellow-50 dark:hover:bg-gray-800"
                        >
                            <div class="w-8 text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ index + 1 }}
                            </div>
                            <Link :href="`/manga/${manga.mangaSlug}`" class="flex flex-1 items-center space-x-3">
                                <img
                                    :src="manga.thumbnail"
                                    :alt="manga.title"
                                    class="h-16 w-12 rounded border-2 border-transparent object-cover transition-all duration-300 group-hover:border-yellow-400"
                                    loading="lazy"
                                />
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="group-hover:text-navy-700 line-clamp-1 text-sm font-medium text-gray-900 transition-colors dark:text-white"
                                    >
                                        {{ manga.title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Chapter {{ manga.latestChapter }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Popular Manhwa -->
                <section>
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-star mr-2 text-yellow-500"></i>Popular Manhwa
                    </h2>
                    <div class="space-y-4">
                        <div
                            v-for="(manga, index) in manhwaPopuler.slice(0, 5)"
                            :key="manga.mangaSlug"
                            class="group hover:to-navy-50 flex items-center space-x-4 rounded-lg border border-transparent p-3 transition-colors hover:border-yellow-200 hover:bg-gradient-to-r hover:from-yellow-50 dark:hover:bg-gray-800"
                        >
                            <div class="w-8 text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ index + 1 }}
                            </div>
                            <Link :href="`/manga/${manga.mangaSlug}`" class="flex flex-1 items-center space-x-3">
                                <img
                                    :src="manga.thumbnail"
                                    :alt="manga.title"
                                    class="h-16 w-12 rounded border-2 border-transparent object-cover transition-all duration-300 group-hover:border-yellow-400"
                                    loading="lazy"
                                />
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="group-hover:text-navy-700 line-clamp-1 text-sm font-medium text-gray-900 transition-colors dark:text-white"
                                    >
                                        {{ manga.title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Chapter {{ manga.latestChapter }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </section>

                <!-- Popular Manhua -->
                <section>
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-gem mr-2 text-yellow-500"></i>Popular Manhua
                    </h2>
                    <div class="space-y-4">
                        <div
                            v-for="(manga, index) in manhuaPopuler.slice(0, 5)"
                            :key="manga.mangaSlug"
                            class="group hover:to-navy-50 flex items-center space-x-4 rounded-lg border border-transparent p-3 transition-colors hover:border-yellow-200 hover:bg-gradient-to-r hover:from-yellow-50 dark:hover:bg-gray-800"
                        >
                            <div class="w-8 text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ index + 1 }}
                            </div>
                            <Link :href="`/manga/${manga.mangaSlug}`" class="flex flex-1 items-center space-x-3">
                                <img
                                    :src="manga.thumbnail"
                                    :alt="manga.title"
                                    class="h-16 w-12 rounded border-2 border-transparent object-cover transition-all duration-300 group-hover:border-yellow-400"
                                    loading="lazy"
                                />
                                <div class="min-w-0 flex-1">
                                    <h3
                                        class="group-hover:text-navy-700 line-clamp-1 text-sm font-medium text-gray-900 transition-colors dark:text-white"
                                    >
                                        {{ manga.title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Chapter {{ manga.latestChapter }}</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Genres Section -->
            <section class="mb-16">
                <h2 class="mb-8 text-3xl font-bold text-gray-900 dark:text-white"><i class="fas fa-tags mr-3 text-yellow-500"></i>Browse by Genre</h2>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                    <Link
                        v-for="genre in genres"
                        :key="genre.slug"
                        :href="`/genre/${genre.slug}`"
                        class="group border-navy-200 hover:to-navy-50 hover:text-navy-700 rounded-lg border bg-white px-4 py-3 text-center transition-all hover:border-yellow-400 hover:bg-gradient-to-r hover:from-yellow-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-yellow-400"
                    >
                        <span class="text-navy-700 group-hover:text-navy-800 text-sm font-medium dark:text-gray-300 dark:group-hover:text-yellow-400">
                            {{ genre.title }}
                        </span>
                    </Link>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-navy-900 text-gray-300">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-8 md:grid-cols-4">
                    <div class="col-span-2">
                        <div class="mb-4 flex items-center space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-600">
                                <i class="fas fa-book-open text-navy-900 text-sm"></i>
                            </div>
                            <span class="text-xl font-bold text-white">MangaHub</span>
                        </div>
                        <p class="mb-4 max-w-md text-gray-400">
                            Your ultimate destination for reading manga, manhwa, and manhua online. Discover thousands of titles updated daily.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 transition-colors hover:text-yellow-400">
                                <i class="fab fa-discord text-lg"></i>
                            </a>
                            <a href="#" class="text-gray-400 transition-colors hover:text-yellow-400">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                            <a href="#" class="text-gray-400 transition-colors hover:text-yellow-400">
                                <i class="fab fa-reddit text-lg"></i>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-4 font-semibold text-white">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="/" class="text-gray-400 transition-colors hover:text-yellow-400">Home</a></li>
                            <li><a href="/popular" class="text-gray-400 transition-colors hover:text-yellow-400">Popular</a></li>
                            <li><a href="/latest" class="text-gray-400 transition-colors hover:text-yellow-400">Latest</a></li>
                            <li><a href="/genres" class="text-gray-400 transition-colors hover:text-yellow-400">Genres</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 font-semibold text-white">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="/contact" class="text-gray-400 transition-colors hover:text-yellow-400">Contact Us</a></li>
                            <li><a href="/faq" class="text-gray-400 transition-colors hover:text-yellow-400">FAQ</a></li>
                            <li><a href="/privacy" class="text-gray-400 transition-colors hover:text-yellow-400">Privacy Policy</a></li>
                            <li><a href="/terms" class="text-gray-400 transition-colors hover:text-yellow-400">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>

                <div class="border-navy-700 mt-8 border-t pt-8 text-center">
                    <p class="text-gray-400">© 2024 MangaHub. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom Navy Color Utilities */
.text-navy-700 {
    color: #1e3a8a;
}

.text-navy-800 {
    color: #1e40af;
}

.text-navy-900 {
    color: #1e293b;
}

.bg-navy-50 {
    background-color: #f8fafc;
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

.border-navy-200 {
    border-color: #e2e8f0;
}

.border-navy-300 {
    border-color: #cbd5e1;
}

.border-navy-700 {
    border-color: #334155;
}

.hover\:border-navy-600:hover {
    border-color: #475569;
}

.hover\:bg-navy-700:hover {
    background-color: #334155;
}

.focus\:ring-yellow-500:focus {
    --tw-ring-opacity: 1;
    --tw-ring-color: rgb(234 179 8 / var(--tw-ring-opacity));
}

/* Hide scrollbar for horizontal scroll */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
