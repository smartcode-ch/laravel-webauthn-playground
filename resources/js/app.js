import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import PrimeVue from 'primevue/config';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import "/node_modules/primeflex/primeflex.css";
import "primevue/resources/themes/viva-light/theme.css";
import "primevue/resources/primevue.min.css";

import AppLayout from './AppLayout.vue'


createInertiaApp({
  resolve: name => {
    return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')).then(page => {

      page.default.layout = AppLayout

      return page
    })
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(PrimeVue)
      .mount(el)

  },
})
