import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Ziggy } from './ziggy';

const appName =
  window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) =>
    await resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<any>('./Pages/**/*.vue')
    ),
  setup({ el, app, props, plugin }) {
    const vueApp = createApp({ render: () => h(app, props) });
    vueApp.use(plugin).use(ZiggyVue, Ziggy).mount(el);
    return vueApp;
  },
});

InertiaProgress.init({ color: '#4B5563' });
