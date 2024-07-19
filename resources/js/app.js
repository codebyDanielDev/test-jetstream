import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createI18n } from 'vue-i18n';

// Importar mensajes de traducción
import en from './locales/en.json';
import es from './locales/es.json';

const messages = {
  en,
  es,
};

// Obtener valores de las variables de entorno modificar en un futuro para obtener ese valor dinamicamente o por preferecniuas del usuario

const locale = import.meta.env.VITE_APP_LOCALE || 'es';
const fallbackLocale = import.meta.env.VITE_APP_FALLBACK_LOCALE || 'es';

// Crear instancia de i18n
const i18n = createI18n({
  locale: locale, // Establecer idioma predeterminado
  fallbackLocale: fallbackLocale,
  messages,
  legacy: false, // Asegurarse de que esté en modo Vue 3 (Composition API)
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
