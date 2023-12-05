<script setup lang="ts">
  import { useDuplicate } from '@/Composables/CommonRoutes'
  import {
    mdiArrowRight,
    mdiContentCopy,
    mdiDotsHorizontal,
    mdiPencil,
    mdiPlusCircleOutline,
    mdiSend,
    mdiTrashCanOutline,
  } from '@mdi/js'
  import route from 'ziggy-js'

  const { t } = useI18n()

  const headers = [
    { key: 'user_id', title: t('creator') },
    { key: 'title', title: t('title') },
    { key: 'description', title: t('description') },
    { key: 'pvc', title: t('pvc-infos') },
    { key: 'stock', title: t('stock-infos') },
    { key: 'updated_at', title: t('updated_at') },
    { key: 'actions', title: t('actions') },
  ]

  const searched = useUrlSearchParams('history').s

  const tab = ref<number>(0)
  const tabs = ref<
    { title: string; items: TPageProps | any; headers: any[]; key: string }[]
  >([
    {
      title: t('menuDrawerItems.my-lists'),
      items: { data: [] },
      headers: headers.slice(1),
      key: 'my-lists',
    },
    {
      title: t('inbox-lists'),
      items: { data: [] },
      headers: headers,
      key: 'inbox',
    },
    {
      title: t('archived'),
      items: { data: [] },
      headers: headers.slice(1),
      key: 'archived',
    },
    {
      title: t('deleted'),
      items: { data: [] },
      headers: headers.slice(1),
      key: 'deleted',
    },
  ])

  const search = ref<string>(searched ? (searched as string) : '')

  const doSearch = () => useSearch('list-cases.index', search.value as string)

  const selectedItems = ref<number[]>([])

  const case_dialog = ref<boolean>(false)
  const case_item = ref<IListCaseItem>(undefined)

  const listCaseDialogActivator = (item: IListCaseItem = undefined) => {
    case_item.value = item
    case_dialog.value = true
  }

  function onCaseClosed() {
    case_dialog.value = false
    case_item.value = undefined
  }

  function onSubmitted(item: IListCaseItem) {
    if (item.created_at === item.updated_at)
      tabs.value[tab.value].items.data.unshift(item)
    else {
      let index = tabs.value[tab.value].items.data.findIndex(
        (Item) => Item.id === item.id,
      )
      tabs.value[tab.value].items[index] = item
    }
  }

  const deleteDialog = ref<boolean>(false)
  const listId = ref<number>(0)

  function deleteList(id: number) {
    listId.value = id
    deleteDialog.value = true
  }

  const doDelete = () =>
    useDelete(
      'list-cases.destroy',
      listId.value !== 0 ? listId.value : selectedItems.value.toString(),
    ).then(() => {
      if (listId.value !== 0)
        _.remove(
          tabs.value[tab.value].items.data,
          (item: IListCaseItem) => item.id === listId.value,
        )
      else {
        selectedItems.value.forEach((item) =>
          _.remove(
            tabs.value[tab.value].items.data,
            (t: IListCaseItem) => t.id === item,
          ),
        )
        selectedItems.value = []
      }
      onCloseDeleteDialog()
    })

  function onCloseDeleteDialog() {
    deleteDialog.value = false
    listId.value = 0
  }

  const duplicate = (id: number) =>
    useDuplicate('list-cases.duplicate', id)
      .then((res: any) => tabs.value[tab.value].items.data.unshift(res))
      .catch((err: string) => console.error(err))

  const fetchLists = async (
    activatedTab: number = tab.value,
    page: number = 1,
  ) =>
    await useFetchClient
      .get<TPageProps>(
        route('list-cases.index.api', {
          type: tabs.value[activatedTab].key,
          _query: { page: page },
        }),
      )
      .then(({ data }) => {
        tabs.value[activatedTab].items = data.value
      })

  function onTabChanged(args: number) {
    selectedItems.value = []
    if (!tabs.value[args].items.data.length) fetchLists(args)
  }

  onMounted(() => fetchLists())
</script>

<template lang="pug">
p-head(:title="$t('menuDrawerItems.my-lists')")
PanelLayout
  .container
    v-row
      v-col(cols="12", md="8")
        | {{ $t('menuDrawerItems.my-lists') }}
        SearchField(
          ::="search",
          :disabled="tab !== 0",
          @do-search="doSearch",
          class="w-1/3"
        ).mt-4
      v-col(class="rtl:text-left", cols="12", md="4")
        v-btn(
          :prepend-icon="mdiArrowRight",
          rounded="lg",
          v-show="searched",
          variant="outlined"
        ).my-auto.ml-3 {{ $t('back') }}
        v-btn(
          :disabled="!selectedItems.length",
          :prepend-icon="mdiTrashCanOutline",
          color="red",
          rounded="lg",
          variant="tonal"
          v-show="tab !== 1"
        ).my-auto.ml-3 {{ $t('delete') }}
        v-btn(
          :prepend-icon="mdiPlusCircleOutline",
          @click="listCaseDialogActivator",
          color="secondary",
          rounded="lg",
          v-show="tab === 0"
        ).my-auto {{ $t('add', { name: $t('list') }) }}
      v-col(cols="12").flex.flex-col.gap-y-2
        v-tabs(::="tab", @update:model-value="onTabChanged", color="secondary")
          v-tab(v-for="item in tabs") {{ item.title }}
        v-window(::="tab")
          v-window-item(:value="index", v-for="(item, index) in tabs")
            v-data-table(
              ::="selectedItems",
              :headers="item.headers",
              :items="item.items.data",
              :show-select="tab === 0 || tab === 2",
              item-value="id"
            )
              template(#bottom)
                Paginator(
                  :current-page="item.items.current_page",
                  :links="item.items.links",
                  :total="item.items.last_page",
                  @page:click="(args:number)=>fetchLists(undefined,args)",
                  type="api"
                )
              template(#item.actions="item")
                v-menu(location="bottom")
                  template(#activator="{ props }")
                    v-btn(
                      :icon="mdiDotsHorizontal",
                      size="small",
                      v-bind="props"
                    )
                  v-list
                    v-list-item(:prepend-icon="mdiSend")
                    v-divider.mx-2
                    v-list-item(
                      :prepend-icon="mdiPencil",
                      @click="listCaseDialogActivator(item.value)"
                    )
                    v-list-item(
                      :prepend-icon="mdiContentCopy",
                      @click="duplicate(item.value.id)"
                    )
                    v-list-item(
                      :prepend-icon="mdiTrashCanOutline",
                      @click="deleteList(item.value.id)"
                    )
              template(#item.updated_at="{ value }")
                | {{ useLocaleTimeAgo(value) }}
              template(#item.title="item")
                p-link(
                  :href="route('list-items.index', { _query: { list_case_id: item.value.id } })",
                  class="hover:underline",
                  v-text="item.value.title"
                ).text-sky-500
              template(#item.user_id="item")
                | {{ item.value.author.name }}
ListCaseDialog(
  :initial-form="case_item",
  :show="case_dialog",
  @close="onCaseClosed",
  @submitted="onSubmitted"
)
ConfirmationModal(
  :show="deleteDialog",
  @close="onCloseDeleteDialog",
  @yes="doDelete"
)
  template(#title)
    | {{ $t('delete', { name: $t('list') }) }}
  template(#content)
    | {{ $t('delete-confirmation', { name: $t('list') }) }}
</template>
