import ThemeProvideVue from './ThemeProvide.vue'
import { mdiAccountOutline } from '@mdi/js'
import { VDivider, VIcon } from 'vuetify/lib/components/index.mjs'
import { useDisplay } from 'vuetify/lib/framework.mjs'
import route from 'ziggy-js'

export const Header = defineComponent({
  setup() {
    const { arrivedState } = useScroll(document)

    const drawer = ref<boolean>(false)

    const { t } = useI18n()

    const menuItem = [
      { title: t('header-nav.features'), url: '#features' },
      { title: t('header-nav.services'), url: '#services' },
      { title: t('header-nav.about-us'), url: '#about-us' },
    ]

    const { mobile } = useDisplay()

    return () => (
      <nav class="fixed w-full top-0 z-30 transition duration-300">
        <div class="container px-18 mx-auto">
          <div
            class={[
              'lg:flex lg:items-center relative transition duration-300 rounded-full mt-4 lg:mx-24 bg-[#fff] dark:bg-gray-800 bg-opacity-0 dark:bg-opacity-0',
              {
                'bg-opacity-100 dark:bg-opacity-100 shadow-md':
                  !arrivedState.top,
              },
            ]}
          >
            <div class="flex items-center justify-between relative">
              <p-link href={route('home.index')} class="absolute right-6 mt-1">
                <img
                  class="w-auto h-6 lg:h-10 dark:brightness-200"
                  src="/assets/img/logo.png"
                  alt="logo"
                />
              </p-link>

              {/* Mobile menu button */}
              <div class="flex lg:hidden">
                <button
                  onClick={() => (drawer.value = !drawer.value)}
                  type="button"
                  class="text-gray-500 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 focus:outline-none focus:text-gray-600 dark:focus:text-gray-400"
                  aria-label="toggle menu"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class={['w-6 h-6', !drawer.value ? 'block' : 'hidden']}
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M4 8h16M4 16h16"
                    />
                  </svg>

                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class={['w-6 h-6', drawer.value ? 'block' : 'hidden']}
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <div
              class={[
                drawer.value
                  ? 'translate-x-0 opacity-100 '
                  : 'opacity-0 -translate-x-full',
                'absolute inset-x-0 z-20 flex-1 w-full px-6 py-4 transition-all duration-300 ease-in-out bg-transparent lg:mt-0 lg:p-0 lg:top-0 lg:relative lg:bg-transparent lg:w-auto lg:opacity-100 lg:translate-x-0 lg:flex lg:items-center lg:justify-center',
              ]}
            >
              <div class="flex flex-col capitalize dark:text-gray-300 lg:flex lg:px-16 lg:-mx-4 lg:flex-row lg:items-center text-gray-600">
                {menuItem.map((item) => (
                  <a
                    href={item.url}
                    class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200"
                  >
                    {item.title}
                  </a>
                ))}
                {mobile.value ? (
                  <>
                    <VDivider class="my-3" />
                    <div class="flex flex-row items-center justify-between gap-x-2">
                      <p-link class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200">
                        {t('auth.login-register')}
                      </p-link>
                      <ThemeProvideVue />
                    </div>
                  </>
                ) : null}
              </div>
            </div>

            <div
              class={[
                'flex justify-center sm:mt-6 lg:flex z-40 lg:mt-0 lg:ml-4 absolute left-0',
                { hidden: mobile.value },
              ]}
            >
              <p-link
                href={route('login')}
                as="button"
                type="button"
                class="border hover:shadow-lg dark:bg-opacity-50 hover:dark:bg-opacity-100 transition duration-250 shadow-cyan-500 my-auto px-2 ml-3 h-9 dark:text-white border-gray-600 flex flex-row items-center justify-center dark:border-gray-200 rounded-full bg-white bg-opacity-20 dark:bg-gray-850 hover:bg-opacity-100"
              >
                <VIcon class="ml-2 text-sky-600">{mdiAccountOutline}</VIcon>
                <VDivider vertical class="ml-2 my-auto" length="24px" />
                <span class="text-xxs">{t('auth.login-register')}</span>
              </p-link>
              <ThemeProvideVue />
            </div>
          </div>
        </div>
      </nav>
    )
  },
})

export const Footer = defineComponent({
  setup() {
    const { d, t } = useI18n()

    return () => (
      <footer class="bg-white dark:bg-gray-900">
        <div class="container px-6 py-8 mx-auto">
          <div class="flex flex-col items-center text-center">
            <p-link href={route('home.index')}>
              <img class="w-auto h-8" src="/assets/img/logo.png" alt="logo" />
            </p-link>

            <p class="mx-auto mt-4 text-xs text-gray-500 dark:text-gray-400">
              {t('pixel-hero-desc')}
            </p>
          </div>

          <VDivider />

          <div class="mt-4 dir-ltr">
            <p class="text-sm text-gray-500 text-center">
              &copy; Copyright {d(new Date(), 'year')} AGXIC. All Rights
              Reserved.
            </p>
          </div>
        </div>
      </footer>
    )
  },
})
