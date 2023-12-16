import type { UseTextDirectionValue } from '@vueuse/core'
import route from 'ziggy-js'

export const useGlobalState = createGlobalState(() => {
  const breadCrumb = ref<IBreadCrumbItem[]>([])

  function setBreadCrumbItem(item: IBreadCrumbItem) {
    breadCrumb.value.push({
      title: item.title,
      icon: item.icon,
      link: item.link,
    })
  }

  const drawerItem = ref<IMenuItem>(null)

  function selectDrawerItem(item: IMenuItem) {
    drawerItem.value = item
    breadCrumb.value = []
    setBreadCrumbItem({
      title: item.title,
      icon: item.icon,
      link: item.link,
    })
  }

  const direction = ref<UseTextDirectionValue>('rtl')

  const drawerOpener = ref<boolean>(false)

  const homeBreadCrumb = ref<string>(route('dashboard'))

  return {
    breadCrumb,
    selectDrawerItem,
    setBreadCrumbItem,
    direction,
    drawerOpener,
    homeBreadCrumb,
  }
})
