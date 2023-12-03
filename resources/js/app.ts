// @ts-ignore
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import '../css/app.css'
import App from './App.vue'
import './bootstrap'
import i18n from './Plugins/i18n'
import vuetify from './Plugins/vuetify'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import VueTransitions from '@morev/vue-transitions'
import '@morev/vue-transitions/styles'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import 'aos/dist/aos.css'
import 'instant.page'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import 'swiper/css'
import 'swiper/css/navigation'
import { createApp, h } from 'vue'
import type { DefineComponent } from 'vue'
import Vue3PersianDatetimePicker from 'vue3-persian-datetime-picker'
import Vue3Toastify, { type ToastContainerOptions } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import { VueReCaptcha } from 'vue-recaptcha-v3'

const appName = window.document.getElementsByTagName('title')[0]?.innerText

const QuillEditorConfig = {
  toolbar: [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    [{ size: ['small', false, 'large', 'huge'] }],
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote'],
    [{ direction: 'rtl' }],
    [{ align: [] }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    [{ color: [] }, { background: [] }],
  ],
}

QuillEditor.props.globalOptions.default = () => QuillEditorConfig

createInertiaApp({
  title: (title) => `${title} | ${appName}`,
  resolve: (name) => {
    const page = resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
    )
    page.then((module) => {
      module.default.layout = module.default.layout || App
    })
    return page
  },
  // @ts-expect-error
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .component('p-link', Link)
      .component('p-head', Head)
      .use(vuetify)
      .use(i18n)
      .use(VueTransitions)
      .use(Vue3Toastify, {
        position: 'bottom-left',
        rtl: true,
        toastClassName: 'dark:bg-dark-200 dark:text-white',
      } as ToastContainerOptions)
      .use(VueReCaptcha, {
        siteKey: import.meta.env.VITE_GOOGLE_RECAPTCHA_SITE_KEY,
        loaderOptions: {
          useRecaptchaNet: true,
          autoHideBadge: true,
        },
      })
      .use(Vue3PersianDatetimePicker, {
        name: 'p-date-picker',
        format: 'YYYY-MM-DD HH:mm:ss',
        inputClass: 'date-picker-orise',
        color: '#01DC84',
      })
      .component('p-editor', QuillEditor)
    return app.mount(el)
  },
  progress: {
    color: '#01DC84',
  },
})
