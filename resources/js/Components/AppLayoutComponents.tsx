import ThemeProvideVue from './ThemeProvide.vue'
import { mdiAccountOutline } from '@mdi/js'
import { VDivider, VIcon } from 'vuetify/lib/components/index.mjs'
import route from 'ziggy-js'

export const Header = defineComponent({
  setup() {
    const { arrivedState } = useScroll(document)

    const drawer = ref<boolean>(false)

    const { t } = useI18n()

    const menuItem = []

    return () => (
      <nav class="fixed w-full top-0 z-30 transition duration-300">
        <div class="container px-18 mx-auto">
          <div
            class={[
              'lg:flex lg:items-center relative transition duration-300 rounded-full mt-4 mx-24 bg-[#fff] dark:bg-gray-800 bg-opacity-0 dark:bg-opacity-0',
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
                  x-cloak
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
              <div
                class={[
                  'flex flex-col capitalize dark:text-gray-300 lg:flex lg:px-16 lg:-mx-4 lg:flex-row lg:items-center',
                  arrivedState.top ? 'text-white' : 'text-gray-600',
                ]}
              >
                <a
                  href="#"
                  class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200"
                >
                  features
                </a>
                <a
                  href="#"
                  class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200"
                >
                  downloads
                </a>
                <a
                  href="#"
                  class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200"
                >
                  docs
                </a>
                <a
                  href="#"
                  class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200"
                >
                  support
                </a>
                <a
                  href="#"
                  class="sm:mt-2 transition-colors duration-300 transform lg:mt-0 lg:mx-4 hover:text-gray-900 dark:hover:text-gray-200"
                >
                  blog
                </a>
              </div>
            </div>

            <div class="flex justify-center sm:mt-6 lg:flex z-40 lg:mt-0 lg:ml-4 absolute left-0">
              <button class="border hover:shadow-lg dark:bg-opacity-50 hover:dark:bg-opacity-100 transition duration-250 shadow-cyan-500 my-auto px-2 ml-3 h-9 dark:text-white border-gray-600 flex flex-row items-center justify-center dark:border-gray-200 rounded-full bg-white bg-opacity-20 dark:bg-gray-850 hover:bg-opacity-100">
                <VIcon class="ml-2 text-sky-600">{mdiAccountOutline}</VIcon>
                <VDivider vertical class="ml-2 my-auto" length="24px" />
                <span>{t('auth.login-register')}</span>
              </button>
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
    const { d } = useI18n()

    return () => (
      <footer class="bg-white dark:bg-gray-900">
        <div class="container px-6 py-8 mx-auto">
          <div class="flex flex-col items-center text-center">
            <p-link href={route('home.index')}>
              <img class="w-auto h-8" src="/assets/img/logo.png" alt="logo" />
            </p-link>

            <p class="max-w-md mx-auto mt-4 text-gray-500 dark:text-gray-400">
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </p>

            <div class="flex flex-col mt-4 sm:flex-row sm:items-center sm:justify-center">
              <button class="flex items-center justify-center order-1 w-full px-2 py-2 mt-3 text-sm tracking-wide text-gray-600 capitalize transition-colors duration-300 transform border rounded-md sm:mx-2 dark:border-gray-400 dark:text-gray-300 sm:mt-0 sm:w-auto hover:bg-gray-50 focus:outline-none focus:ring dark:hover:bg-gray-800 focus:ring-gray-300 focus:ring-opacity-40">
                <svg
                  class="w-5 h-5 mx-1"
                  viewBox="0 0 24 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C21.9939 17.5203 17.5203 21.9939 12 22ZM4 12.172C4.04732 16.5732 7.64111 20.1095 12.0425 20.086C16.444 20.0622 19.9995 16.4875 19.9995 12.086C19.9995 7.68451 16.444 4.10977 12.0425 4.086C7.64111 4.06246 4.04732 7.59876 4 12V12.172ZM10 16.5V7.5L16 12L10 16.5Z"
                    fill="currentColor"
                  ></path>
                </svg>

                <span class="mx-1">View Demo</span>
              </button>

              <button class="w-full px-5 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-300 transform bg-sky-600 rounded-md sm:mx-2 sm:order-2 sm:w-auto hover:bg-sky-500 focus:outline-none focus:ring focus:ring-sky-300 focus:ring-opacity-80">
                Get started
              </button>
            </div>
          </div>

          <hr class="my-10 border-gray-200 dark:border-gray-700" />

          <div
            class="flex flex-col items-center sm:flex-row sm:justify-between"
            dir="ltr"
          >
            <p class="text-sm text-gray-500">
              &copy; Copyright {d(new Date(), 'year')} AGXIC. All Rights
              Reserved.
            </p>

            <div class="flex mt-3 -mx-2 sm:mt-0">
              <a
                href="#"
                class="mx-2 text-sm text-gray-500 transition-colors duration-300 hover:text-gray-500 dark:hover:text-gray-300"
                aria-label="Reddit"
              >
                {' '}
                Teams{' '}
              </a>

              <a
                href="#"
                class="mx-2 text-sm text-gray-500 transition-colors duration-300 hover:text-gray-500 dark:hover:text-gray-300"
                aria-label="Reddit"
              >
                {' '}
                Privacy{' '}
              </a>

              <a
                href="#"
                class="mx-2 text-sm text-gray-500 transition-colors duration-300 hover:text-gray-500 dark:hover:text-gray-300"
                aria-label="Reddit"
              >
                {' '}
                Cookies{' '}
              </a>
            </div>
          </div>
        </div>
      </footer>
    )
  },
})
