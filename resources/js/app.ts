import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

// Import Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faSearch, faBookOpen, faFire, faChartLine, faRandom, faHeart, faStar, faEye, faChevronLeft, faChevronRight, faFilter, faList, faGlobe, faLanguage, faBookmark, faCalendarAlt, faUser } from '@fortawesome/free-solid-svg-icons';
import { faCompass, faBookmark as farBookmark } from '@fortawesome/free-regular-svg-icons';

// Register Font Awesome icons
library.add(
    faSearch, faBookOpen, faFire, faChartLine, faRandom, faCompass, 
    faHeart, faStar, faEye, faChevronLeft, faChevronRight, faFilter, 
    faList, faGlobe, faLanguage, faBookmark, farBookmark, faCalendarAlt, faUser
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        // Register Font Awesome component globally
        app.component('FontAwesomeIcon', FontAwesomeIcon);
        
        app.use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
