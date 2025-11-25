<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    BookOpen,
    ChevronDown,
    ChevronRight,
    Clock,
    Github,
    Heart,
    Home,
    Library,
    Menu,
    MessageCircle,
    Search,
    Twitter,
    Users,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';

// Sidebar state
const isSidebarOpen = ref(false);
const isMobile = ref(false);

// Search bar state
const searchQuery = ref('');

// Auto-hide header state
const lastScrollY = ref(0);
const isHidden = ref(false);
const ticking = ref(false);

// Collapsible menu states
const isLibraryOpen = ref(false);
const isCommunityOpen = ref(false);

function submitSearch() {
    if (searchQuery.value.trim() !== '') {
        router.get('/search', { q: searchQuery.value });
        searchQuery.value = '';
    }
}

function toggleSidebar() {
    isSidebarOpen.value = !isSidebarOpen.value;
}

function checkMobile() {
    isMobile.value = window.innerWidth < 1024;
    if (!isMobile.value && isSidebarOpen.value) {
        // Keep sidebar open on desktop
    }
}

function handleResize() {
    checkMobile();
}

// Auto-hide header functionality
function handleScroll() {
    if (!ticking.value) {
        requestAnimationFrame(updateHeader);
        ticking.value = true;
    }
}

function updateHeader() {
    const currentScrollY = window.scrollY;

    // Don't hide header if we're at the top or scrolled very little
    if (currentScrollY < 50) {
        isHidden.value = false;
    } else if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        // Scrolling down - hide header
        isHidden.value = true;
    } else if (currentScrollY < lastScrollY.value - 10) {
        // Scrolling up - show header
        isHidden.value = false;
    }

    lastScrollY.value = currentScrollY;
    ticking.value = false;
}

// Lifecycle hooks
onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('resize', handleResize);
    lastScrollY.value = window.scrollY;
    checkMobile();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleResize);
});

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: '/',
        icon: Home,
    },
    {
        title: 'Follows',
        href: '/follows',
        icon: Heart,
    },
    {
        title: 'Updates',
        href: '/updates',
        icon: Clock,
    },
    {
        title: 'Titles',
        href: '/titles',
        icon: BookOpen,
    },
];

const libraryItems: NavItem[] = [
    {
        title: 'Bookmarks',
        href: '/bookmark',
        icon: BookOpen,
    },
    {
        title: 'Reading History',
        href: '/reading-history',
        icon: Clock,
    },
    {
        title: 'My Library',
        href: '/library',
        icon: Library,
    },
];

const communityItems: NavItem[] = [
    {
        title: 'Forums',
        href: '/forums',
        icon: MessageCircle,
    },
    {
        title: 'Statistics',
        href: '/stats',
        icon: BarChart3,
    },
    {
        title: 'Groups',
        href: '/groups',
        icon: Users,
    },
];

const socialLinks = [
    {
        title: 'GitHub',
        href: 'https://github.com',
        icon: Github,
    },
    {
        title: 'Twitter',
        href: 'https://twitter.com',
        icon: Twitter,
    },
    {
        title: 'Discord',
        href: 'https://discord.com',
        icon: MessageCircle,
    },
];
</script>

<template>
    <div>
        <!-- Mobile Overlay -->
        <div
            v-if="isSidebarOpen && isMobile"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm transition-opacity duration-300"
            @click="toggleSidebar"
        ></div>

        <!-- Sidebar -->
        <div
            class="fixed top-0 left-0 z-50 h-full w-72 transform bg-neutral-900 shadow-2xl transition-transform duration-300 ease-out"
            :class="{
                '-translate-x-full': !isSidebarOpen,
                'translate-x-0': isSidebarOpen,
            }"
        >
            <!-- Sidebar Header -->
            <div class="flex h-16 items-center justify-between border-b border-neutral-800 px-6">
                <Link href="/" class="flex items-center gap-3" @click="isMobile && toggleSidebar()">
                    <AppLogoIcon class="h-8 w-8 fill-current text-orange-500" />
                    <span class="text-xl font-bold text-white">MangaPhase</span>
                </Link>
                <Button
                    v-if="isMobile"
                    variant="ghost"
                    size="icon"
                    class="h-8 w-8 text-neutral-400 hover:bg-neutral-800 hover:text-white lg:hidden"
                    @click="toggleSidebar"
                >
                    <ChevronRight class="h-4 w-4" />
                </Button>
            </div>

            <!-- Sidebar Content -->
            <div class="flex h-[calc(100%-4rem)] flex-col">
                <nav class="flex-1 space-y-1 px-3 py-4">
                    <!-- Main Navigation -->
                    <div class="space-y-1">
                        <Link
                            v-for="item in mainNavItems"
                            :key="item.title"
                            :href="item.href"
                            class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all duration-200 hover:bg-neutral-800 hover:text-white"
                            :class="{
                                'border-r-2 border-orange-500 bg-orange-500/20 text-orange-400': isCurrentRoute(item.href),
                                'hover:text-orange-300': isCurrentRoute(item.href),
                            }"
                            @click="isMobile && toggleSidebar()"
                        >
                            <component :is="item.icon" class="h-5 w-5 flex-shrink-0" />
                            {{ item.title }}
                        </Link>
                    </div>

                    <!-- Library Section -->
                    <div class="pt-4">
                        <Collapsible v-model:open="isLibraryOpen">
                            <CollapsibleTrigger
                                class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all duration-200 hover:bg-neutral-800 hover:text-white"
                            >
                                <Library class="h-5 w-5 flex-shrink-0" />
                                <span class="flex-1 text-left">Library</span>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': isLibraryOpen }" />
                            </CollapsibleTrigger>
                            <CollapsibleContent class="ml-8 space-y-1 pt-1">
                                <Link
                                    v-for="item in libraryItems"
                                    :key="item.title"
                                    :href="item.href"
                                    class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-neutral-400 transition-all duration-200 hover:bg-neutral-800 hover:text-neutral-200"
                                    :class="{
                                        'bg-orange-500/10 text-orange-400': isCurrentRoute(item.href),
                                    }"
                                    @click="isMobile && toggleSidebar()"
                                >
                                    <component :is="item.icon" class="h-4 w-4 flex-shrink-0" />
                                    {{ item.title }}
                                </Link>
                            </CollapsibleContent>
                        </Collapsible>
                    </div>

                    <!-- Community Section -->
                    <div class="pt-2">
                        <Collapsible v-model:open="isCommunityOpen">
                            <CollapsibleTrigger
                                class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all duration-200 hover:bg-neutral-800 hover:text-white"
                            >
                                <Users class="h-5 w-5 flex-shrink-0" />
                                <span class="flex-1 text-left">Community</span>
                                <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': isCommunityOpen }" />
                            </CollapsibleTrigger>
                            <CollapsibleContent class="ml-8 space-y-1 pt-1">
                                <Link
                                    v-for="item in communityItems"
                                    :key="item.title"
                                    :href="item.href"
                                    class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-neutral-400 transition-all duration-200 hover:bg-neutral-800 hover:text-neutral-200"
                                    :class="{
                                        'bg-orange-500/10 text-orange-400': isCurrentRoute(item.href),
                                    }"
                                    @click="isMobile && toggleSidebar()"
                                >
                                    <component :is="item.icon" class="h-4 w-4 flex-shrink-0" />
                                    {{ item.title }}
                                </Link>
                            </CollapsibleContent>
                        </Collapsible>
                    </div>
                </nav>

                <!-- Sidebar Footer -->
                <div class="border-t border-neutral-800 p-4">
                    <div class="mb-4 flex items-center justify-center gap-4">
                        <a
                            v-for="link in socialLinks"
                            :key="link.title"
                            :href="link.href"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-neutral-500 transition-colors duration-200 hover:text-orange-400"
                        >
                            <component :is="link.icon" class="h-4 w-4" />
                        </a>
                    </div>
                    <div class="text-center text-xs text-neutral-500">
                        <div>MangaPhase v2.0</div>
                        <div class="mt-1">
                            <a href="/terms" class="hover:text-neutral-300">Terms</a> •
                            <a href="/privacy" class="hover:text-neutral-300">Privacy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div
            class="transition-all duration-300 ease-out"
            :class="{
                'ml-72': isSidebarOpen,
                'ml-0': !isSidebarOpen,
            }"
        >
            <!-- Top Header -->
            <div
                class="fixed top-0 right-0 z-30 transition-all duration-300 ease-out will-change-transform"
                :class="{
                    '-translate-y-full': isHidden,
                    'left-72': isSidebarOpen,
                    'left-0': !isSidebarOpen,
                }"
            >
                <div class="border-b border-neutral-200/20 bg-neutral-900/80 backdrop-blur-md dark:border-neutral-800/40">
                    <div class="flex h-16 items-center justify-between px-4">
                        <!-- Left: Hamburger Menu -->
                        <div class="flex items-center">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="h-9 w-9 text-neutral-300 hover:bg-neutral-800/50 hover:text-white"
                                @click="toggleSidebar"
                            >
                                <Menu class="h-5 w-5" />
                            </Button>
                        </div>

                        <!-- Center: Search Bar -->
                        <div class="mx-4 max-w-md flex-1">
                            <div class="relative">
                                <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 transform text-neutral-400" />
                                <input
                                    v-model="searchQuery"
                                    @keyup.enter="submitSearch"
                                    type="text"
                                    placeholder="Search manga..."
                                    class="w-full rounded-lg border border-neutral-700/50 bg-neutral-800/50 py-2.5 pr-4 pl-10 text-white backdrop-blur-sm transition-all duration-200 placeholder:text-neutral-400 focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/50 focus:outline-none"
                                />
                            </div>
                        </div>

                        <!-- Right: User Menu -->
                        <div class="flex items-center gap-2">
                            <template v-if="auth.user">
                                <DropdownMenu>
                                    <DropdownMenuTrigger :as-child="true">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            class="relative h-10 w-10 rounded-full p-0.5 ring-2 ring-transparent transition-all duration-200 hover:ring-orange-500/50"
                                        >
                                            <Avatar class="h-8 w-8 overflow-hidden rounded-full">
                                                <AvatarImage v-if="auth.user && auth.user.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
                                                <AvatarFallback class="rounded-full bg-neutral-700 font-semibold text-white">
                                                    {{ getInitials(auth.user?.name) }}
                                                </AvatarFallback>
                                            </Avatar>
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent
                                        align="end"
                                        class="w-56 border border-neutral-700/50 bg-neutral-800/95 shadow-xl backdrop-blur-md"
                                    >
                                        <UserMenuContent :user="auth.user" />
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </template>
                            <template v-else>
                                <Link :href="route('login')">
                                    <Button variant="ghost" class="text-neutral-300 hover:bg-neutral-800/50 hover:text-white">Login</Button>
                                </Link>
                                <Link :href="route('register')">
                                    <Button class="bg-orange-600 text-white shadow-lg transition-all duration-200 hover:bg-orange-700"
                                        >Register</Button
                                    >
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div
            v-if="props.breadcrumbs.length > 1"
            class="transition-all duration-300 ease-out"
            :class="{
                'ml-72': isSidebarOpen,
                'ml-0': !isSidebarOpen,
            }"
        >
            <div class="border-b border-neutral-700/40 bg-neutral-900/70 px-4 py-3 backdrop-blur-md">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>

        <!-- Content Padding -->
        <div
            class="transition-all duration-300 ease-out"
            :class="{
                'ml-72': isSidebarOpen,
                'ml-0': !isSidebarOpen,
            }"
        >
            <div class="h-16" :class="{ 'h-28': props.breadcrumbs.length > 1 }"></div>
        </div>
    </div>
    <!-- </div> -->
</template>
