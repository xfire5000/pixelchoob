import i18n from './i18n'
import { useI18n } from 'vue-i18n'
import { createVuetify } from 'vuetify'
import { aliases, mdi } from 'vuetify/iconsets/mdi-svg'
import { createVueI18nAdapter } from 'vuetify/locale/adapters/vue-i18n'
import 'vuetify/styles'

export default createVuetify({
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: { mdi },
  },
  theme: {
    themes: {
      light: {
        dark: false,
        colors: {
          surface: '#fff',
          background: '#fff',
          primary: '#01DC84',
          secondary: '#30DAD0',
          error: '#f53939',
        },
      },
      dark: {
        dark: true,
        colors: {
          background: '#0A302E',
          primary: '#01DC84',
          secondary: '#30DAD0',
          error: '#f53939',
        },
      },
    },
  },
  ssr: true,
  locale: {
    adapter: createVueI18nAdapter({ i18n, useI18n }),
  },
  defaults: {
    global: { ripple: true },
    VTextField: {
      density: 'compact',
      variant: 'outlined',
    },
    VTextArea: {
      density: 'compact',
      class: '',
      variant: 'outlined',
    },
    VSelect: {
      density: 'compact',
      class: '',
    },
    VAutocomplete: {
      density: 'compact',
      class: '',
    },
    VAlert: { density: 'compact' },
    VList: {
      density: 'compact',
      class: 'dark:bg-dark-200 dark:text-gray-100',
    },
    VListItem: {
      density: 'compact',
      class: 'text-sm',
    },
    VTable: {
      density: 'compact',
      class: 'dark:bg-dark-300 dark:text-gray-300',
    },
    VSwitch: { density: 'compact', class: 'dark:child:text-gray-200' },
    VChip: { size: 'small', class: 'mx-2' },
    VPagination: { density: 'compact' },
    VRadioGroup: {
      density: 'compact',
      color: 'info',
      class: 'dark:text-gray-200',
    },
    VCheckBoxBtn: { density: 'compact', color: 'info' },
    VCardTitle: { class: 'dark:text-gray-300' },
    VTabs: { class: 'dark:text-gray-200 rounded-md' },
    VDivider: { class: 'my-0 dark:text-white' },
    VBtn: { class: 'shadow-none' },
    VCard: { class: 'dark:bg-dark-200' },
    VMenu: { class: 'cursor-default' },
  },
})
