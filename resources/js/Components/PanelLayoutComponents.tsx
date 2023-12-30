import ThemeProvideVue from './ThemeProvide.vue'
import { usePage } from '@inertiajs/vue3'
import {
  mdiAccountGroup,
  mdiArrowLeft,
  mdiChevronLeft,
  mdiCogOutline,
  mdiFolderOutline,
  mdiHomeOutline,
  mdiMenu,
  mdiPageNextOutline,
} from '@mdi/js'
import { VBtn, VIcon } from 'vuetify/lib/components/index.mjs'
import { useDisplay } from 'vuetify/lib/framework.mjs'
import route from 'ziggy-js'

export const BreadCrumb = defineComponent({
  setup() {
    const { breadCrumb, homeBreadCrumb, drawerOpener } = useGlobalState()

    return () => (
      <div class="flex items-center overflow-x-auto whitespace-nowrap py-4 child:text-xs">
        <div onClick={() => (drawerOpener.value = !drawerOpener.value)}>
          {!drawerOpener.value ? (
            <VBtn icon={mdiMenu} variant="text"></VBtn>
          ) : null}
        </div>
        <p-link
          href={homeBreadCrumb.value}
          class="hover:text-primary dark:text-gray-200 text-gray-600"
        >
          <VIcon>{mdiHomeOutline}</VIcon>
        </p-link>
        {breadCrumb.value.length ? (
          <span class="ltr:-scale-x-100 dark:text-gray-300 mx-2 text-gray-500">
            <VIcon class="h-5 w-5">{mdiChevronLeft}</VIcon>
          </span>
        ) : null}
        {breadCrumb.value.map((item, index) => (
          <>
            {breadCrumb.value.length - 1 > index &&
            breadCrumb.value.length !== 1 &&
            item.link ? (
              <p-link
                class="hover:underline dark:text-gray-200 -px-2 flex items-center text-gray-600"
                href={item.link}
              >
                <VIcon size={15} class="rtl:ml-2">
                  {item.icon}
                </VIcon>
                {item.title}
              </p-link>
            ) : (
              <div class="-px-2 flex items-center text-gray-600 dark:text-gray-200">
                <span>{item.title}</span>
              </div>
            )}
            <span
              class={[
                'mx-2 text-gray-500 dark:text-gray-300 ltr:-scale-x-100',
                { hidden: index > breadCrumb.value.length - 2 },
              ]}
            >
              <VIcon class="h-5 w-5">{mdiChevronLeft}</VIcon>
            </span>
          </>
        ))}
      </div>
    )
  },
})

export const NavDrawer = defineComponent({
  setup() {
    const page = usePage()

    const permissions: any[] = page.props.auth['can']

    const { t } = useI18n()

    const { mobile } = useDisplay()

    const { selectDrawerItem, drawerOpener } = useGlobalState()

    const menuItems: {
      title: string
      link: string
      icon: string
      can: boolean
    }[] = [
      {
        title: t('dashboard'),
        link: route('dashboard'),
        icon: mdiHomeOutline,
        can: true,
      },
      {
        title: t('menuDrawerItems.my-lists'),
        link: route('list-case.index'),
        icon: mdiFolderOutline,
        can: true,
      },
      // {
      //   title: t('menuDrawerItems.my-invoices'),
      //   link: route('invoices.index'),
      //   icon: mdiPageNextOutline,
      //   can:true
      // },
      {
        title: t('settings.index'),
        link: route('settings.index'),
        icon: mdiCogOutline,
        can: true,
      },
      {
        title: t('users'),
        link: route('users.index'),
        icon: mdiAccountGroup,
        can: permissions['view-users'],
      },
    ]

    const search = ref<string>('')

    onMounted(() => {
      let itemFinder = menuItems.find(
        (item) => item.link === page.props.ziggy['url'] + page.url,
      )
      if (itemFinder) selectDrawerItem(itemFinder)
      else if (page.url.includes('list-items')) selectDrawerItem(menuItems[1])
      setTimeout(() => (drawerOpener.value = !mobile.value), 500)
    })

    return () => (
      <aside
        class={[
          'sticky transition duration-300 right-0 top-0 z-20 flex flex-col h-screen py-6 overflow-y-auto bg-white border-t-0 border-b-0 rtl:border-r-0 ltr:border-l-0 border dark:bg-gray-900 dark:border-gray-700',
          !drawerOpener.value ? 'w-0' : 'w-64 px-5',
        ]}
      >
        <div
          class="absolute top-2 left-2 block lg:hidden"
          onClick={() => (drawerOpener.value = !drawerOpener.value)}
        >
          <VBtn
            icon={mdiArrowLeft}
            variant="text"
            class="dark:text-white"
          ></VBtn>
        </div>
        <div class="absolute top-4 right-2">
          <ThemeProvideVue />
        </div>
        <p-link href={route('dashboard')} class="mx-auto">
          <img
            class="w-auto h-7 dark:brightness-200"
            src="/assets/img/logo.png"
            alt="logo"
          />
        </p-link>

        <div class="flex flex-col justify-between flex-1 mt-6">
          <nav class="flex-1 -mx-3 space-y-3">
            <div class="relative mx-3">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg
                  class="w-5 h-5 text-gray-400"
                  viewBox="0 0 24 24"
                  fill="none"
                >
                  <path
                    d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  ></path>
                </svg>
              </span>

              <input
                value={search.value}
                onInput={(e) => (search.value = e.target['value'])}
                type="text"
                class="w-full py-1.5 pl-10 pr-4 text-gray-700 bg-white border rounded-md dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:ring-opacity-40 focus:outline-none focus:ring"
                placeholder={t('search-placeholder')}
              />
            </div>

            {menuItems
              .filter((f) =>
                search.value.length
                  ? f.title.includes(search.value) && f.can
                  : f.can,
              )
              .map((item) => (
                <p-link
                  as="button"
                  type="button"
                  class={[
                    'flex items-center px-3 py-2 text-gray-600 transition-colors duration-300 transform rounded-lg dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700 w-full',
                    {
                      'dark:text-gray-200 text-gray-700 dark:bg-gray-800 bg-gray-100':
                        item.link === page.props.ziggy['location'],
                    },
                  ]}
                  href={item.link}
                >
                  <VIcon size="20">{item.icon}</VIcon>

                  <span class="mx-2 text-sm font-medium">{item.title}</span>
                </p-link>
              ))}
          </nav>

          <div class="mt-6">
            <div class="flex items-center justify-between mt-6">
              <p-link
                href={route('profile.show')}
                type="button"
                as="button"
                class="flex items-center gap-x-2"
              >
                <img
                  class="object-cover rounded-full h-7 w-7"
                  src={page.props.auth['user'].profile_photo_url}
                  alt="avatar"
                />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                  {page.props.auth['user'].name}
                </span>
              </p-link>

              <p-link
                method="post"
                as="button"
                type="button"
                href={route('logout')}
                class="text-gray-500 transition-colors duration-200 rotate-180 dark:text-gray-400 rtl:rotate-0 hover:text-blue-500 dark:hover:text-blue-400"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"
                  />
                </svg>
              </p-link>
            </div>
          </div>
        </div>
      </aside>
    )
  },
})
