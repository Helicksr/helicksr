import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Ziggy } from './ziggy';

const appName = 'Helicksr';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) =>
    await resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<any>('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    const vueApp = createApp({ render: () => h(App, props) });
    vueApp.use(plugin).use(ZiggyVue, Ziggy).mount(el);
    return vueApp;
  },
  progress: {
    color: '#4B5563',
  },
})
  .then(() => {})
  .catch((error) => {
    console.error(error);
  });
