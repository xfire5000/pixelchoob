import type { UseTextDirectionValue } from '@vueuse/core'

interface IChildItem extends ILinkMenuItem {
  group?: { title: string; key: number }
}

export interface IMenuItem extends ILinkMenuItem {
  childs?: IChildItem[]
}

export const useGlobalState = createGlobalState(() => {
  const drawerItem = ref<IMenuItem>(null)

  const breadCrumb = ref<IBreadCrumbItem[]>([])

  function setBreadCrumbItem(item: IBreadCrumbItem) {
    breadCrumb.value.push({
      title: item.title,
      icon: item.icon,
      link: item.link,
    })
  }

  function selectDrawerItem(item: IMenuItem, currentLink: string) {
    drawerItem.value = item
    breadCrumb.value = []
    setBreadCrumbItem({
      title: item.title,
      icon: item.icon,
      link: item.link,
    })
    if (item.childs) {
      let childItem = item.childs.find((child) => child.link === currentLink)
      setBreadCrumbItem({
        title: childItem.title,
        icon: childItem.icon,
      })
    }
  }

  const direction = ref<UseTextDirectionValue>('rtl')

  const drawerOpener = ref<boolean>(false)

  const homeBreadCrumb = ref<string>('')

  const readProgress = ref<boolean>(false)

  return {
    drawerItem,
    breadCrumb,
    selectDrawerItem,
    direction,
    drawerOpener,
    homeBreadCrumb,
    readProgress,
    clearNavItems,
  }
})
