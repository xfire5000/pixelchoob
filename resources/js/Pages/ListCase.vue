<script setup lang="ts">
  import {
    mdiArchive,
    mdiArrowRight,
    mdiContentCopy,
    mdiDotsHorizontal,
    mdiFormatListText,
    mdiInboxArrowDownOutline,
    mdiMagnify,
    mdiPencil,
    mdiPlusCircleOutline,
    mdiRestore,
    mdiSend,
    mdiTrashCanOutline,
  } from '@mdi/js'
  import { StatusCodes } from 'http-status-codes'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  const { t } = useI18n()

  const headers = [
    { key: 'user_id', title: t('creator') },
    { key: 'list_items_count', title: t('parts') },
    { key: 'title', title: t('title') },
    { key: 'description', title: t('description') },
    { key: 'pvc', title: t('pvc-infos') },
    { key: 'stock', title: t('stock-infos') },
    { key: 'created_at', title: t('created_at') },
    { key: 'actions', title: t('actions') },
  ]

  const searched = useUrlSearchParams('history').s

  const tab = ref<number>(0)
  const tabs = ref<
    {
      title: string
      items: TPageProps | any
      headers: any[]
      key: string
      icon: string
    }[]
  >([
    {
      title: t('menuDrawerItems.my-lists'),
      items: { data: [] },
      headers: headers.slice(1),
      key: 'my-lists',
      icon: mdiFormatListText,
    },
    {
      title: t('inbox-lists'),
      items: { data: [] },
      headers: headers,
      key: 'inbox',
      icon: mdiInboxArrowDownOutline,
    },
    {
      title: t('archived'),
      items: { data: [] },
      headers: headers.slice(1),
      key: 'archived',
      icon: mdiArchive,
    },
    {
      title: t('deleted'),
      items: { data: [] },
      headers: headers.slice(1),
      key: 'deleted',
      icon: mdiTrashCanOutline,
    },
    {
      title: t('searched'),
      items: { data: [] },
      headers: headers,
      key: 'searched',
      icon: mdiMagnify,
    },
  ])

  const search = ref<string>(searched ? (searched as string) : '')

  const selectedItems = ref<number[]>([])

  const case_dialog = ref<boolean>(false)
  const case_item = ref<IListCaseItem>(undefined)

  const listCaseDialogActivator = (item = undefined) => {
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
    useChangeRoute('list-cases.duplicate', id).then(
      ({ data, statusCode, error }) =>
        StatusCodes.OK === statusCode.value
          ? tabs.value[tab.value].items.data.unshift(data)
          : console.error(error),
    )

  const fetchLists = async (
    activatedTab: number = tab.value,
    page: number = 1,
  ) =>
    await useFetchClient
      .get<TPageProps>(
        route('list-cases.index', {
          _query: _.merge(
            { type: tabs.value[activatedTab].key, page: page },
            search.value.length ? { s: search.value } : null,
          ),
        }),
      )
      .then(({ data }) => (tabs.value[activatedTab].items = data.value))

  function onTabChanged(args: number) {
    selectedItems.value = []
    if (!tabs.value[args].items.links) fetchLists(args)
  }

  onMounted(() => {
    switch (useUrlSearchParams('history').type) {
      default:
        break
      case 'inbox':
        tab.value = 1
        break
      case 'my-lists':
        tab.value = 0
        break
    }
    fetchLists()
  })

  const onPageChanged = (args: number) => fetchLists(undefined, args)

  const restoreList = (id: number) =>
    useChangeRoute('list-cases.restore', id).then(({ statusCode }) => {
      if (statusCode.value === StatusCodes.NO_CONTENT) {
        let item: IListCaseItem = tabs.value[tab.value].items.data.find(
          (d: IListCaseItem) => d.id === id,
        )
        item.deleted_at = null
        tabs.value[0].items.data.push(item)
        _.remove(
          tabs.value[tabs.value.length - 1].items.data,
          (d: IListCaseItem) => d.id === id,
        )
      }
    })

  const contactsDialog = ref<boolean>(false)

  function initOrDestroyToSend(id: number = 0) {
    contactsDialog.value = id !== 0
    listId.value = id
  }

  async function onSelectedUser(id: number) {
    await useFetchClient
      .post<{ msg: string }>(route('list-cases.send'), { user_id: id })
      .then(({ data }) => {
        contactsDialog.value = false
        toast(data.value.msg, { type: 'success' })
        let index = tabs.value[tab.value].items.data.findIndex(
          (item) => item.id === listId.value,
        )
        tabs.value[tab.value].items.data[index].user_id = id
        listId.value = 0
      })
  }
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
          @do-search="fetchLists(tabs.length - 1)",
          class="w-1/3"
        ).mt-4
      v-col(class="rtl:text-left", cols="12", md="4")
        p-link(:href="route('list-cases.index')", v-if="searched")
          v-btn(
            :prepend-icon="mdiArrowRight",
            rounded="lg",
            variant="outlined"
          ).my-auto.ml-3 {{ $t('back') }}
        v-btn(
          :disabled="!selectedItems.length",
          :prepend-icon="mdiTrashCanOutline",
          color="red",
          rounded="lg",
          v-show="tab !== 1",
          variant="tonal"
        ).my-auto.ml-3 {{ $t('delete') }}
        v-btn(
          :prepend-icon="mdiPlusCircleOutline",
          @click="listCaseDialogActivator",
          color="secondary",
          rounded="lg",
          v-show="tab === 0"
        ).my-auto {{ $t('add', { name: $t('list') }) }}
      v-col(cols="12").flex.flex-col.gap-y-2
        v-tabs(::="tab", @update:model-value="onTabChanged", color="sky-600")
          v-tab(
            v-for="item in tabs.filter((f) => (search.length > 0 ? f.key === 'searched' : f.key !== 'searched'))"
          )
            v-icon.ml-2 {{ item.icon }}
            | {{ item.title }}
        v-window(::="tab")
          v-window-item(:value="index", v-for="(item, index) in tabs")
            v-data-table(
              ::="selectedItems",
              :headers="item.headers",
              :items="item.items.data",
              :items-per-page="item.items.per_page",
              :show-select="tab === 0 || tab === 2",
              item-value="id"
            )
              template(#bottom)
                Paginator(
                  :current-page="item.items.current_page",
                  :links="item.items.links",
                  :total="item.items.last_page",
                  @page:click="onPageChanged",
                  type="api"
                ).w-full.p-4
              template(#item.list_items_count="{ value }")
                v-chip(
                  v-text="value"
                ).bg-sky-600.bg-opacity-30.text-sky-600.backdrop-blur-sm
              template(#item.stock="{ value }")
                .flex.flex-col.text-xs
                  .flex.flex-row.gap-x-2
                    span {{ $t('sizes.width') }}: {{ useJsonParser(value).sizes.w }}
                    small {{ $t('units.cm') }}
                    v-divider(vertical).mx-1
                    span {{ $t('sizes.height') }}: {{ useJsonParser(value).sizes.h }}
                    small {{ $t('units.cm') }}
                  v-divider.my-1
                  .flex.flex-row.gap-x-2
                    span {{ $t('qty') }}: {{ useJsonParser(value).qty }}
                    v-divider(vertical).mx-1
                    span {{ $t('stock-pattern') }}: {{ useJsonParser(value).pattern ? $t('having.have-it') : $t('having.not-have-it') }}
                    v-divider(vertical).mx-1
                    span {{ $t('material') }}: {{ useJsonParser(value).material }}
                  v-divider.my-1
                  span {{ $t('color') }}: {{ useJsonParser(value).color }}
              template(#item.pvc="{ value }")
                .flex.flex-col.text-xs
                  .flex.flex-row.gap-x-1
                    input(
                      :value="useJsonParser(value).reduce_thickness",
                      disabled,
                      type="checkbox"
                    )
                    label {{ $t('reduce_thickness') }}
                  v-divider.my-1
                  .flex.flex-row.items-center
                    span {{ $t('sizes.stroke') }}:
                    strong {{ useJsonParser(value).size === '1' ? $t('pvc-size.1mm') : $t('pvc-size.2mm') }}
                  v-divider.my-1
                  strong {{ $t('color-code') }}: {{ useJsonParser(value).color_code }}
              template(#item.description="{ value }")
                p.line-clamp-3.max-w-40 {{ value }}
              template(#item.actions="{ item }")
                v-menu(location="bottom")
                  template(#activator="{ props }")
                    v-btn(
                      :icon="mdiDotsHorizontal",
                      size="small",
                      v-bind="props",
                      variant="text"
                    )
                  v-list
                    v-list-item(
                      :disabled="item['list_items_count'] === 0",
                      :prepend-icon="mdiSend",
                      @click="initOrDestroyToSend(item['id'])",
                      v-if="item['user_id'] === null",
                      v-show="tab === 0"
                    ) {{ $t('send') }}
                    v-list-item(
                      :prepend-icon="mdiRestore",
                      @click="restoreList(item['id'])",
                      v-show="tab === tabs.length - 1"
                    ) {{ $t('restore') }}
                    v-divider.mx-2
                    v-list-item(
                      :prepend-icon="mdiPencil",
                      @click="listCaseDialogActivator(item)",
                      v-if="item['user_id'] === null"
                    ) {{ $t('edit') }}
                    v-list-item(
                      :prepend-icon="mdiContentCopy",
                      @click="duplicate(item['id'])",
                      v-show="tab !== tabs.length - 1"
                    ) {{ $t('duplicate') }}
                    v-list-item(
                      :prepend-icon="mdiTrashCanOutline",
                      @click="deleteList(item['id'])",
                      v-if="item['user_id'] === null",
                      v-show="tab !== 1"
                    ) {{ $t('delete') }}
              template(#item.created_at="{ value }")
                .flex.flex-col.items-center.gap-y-2
                  small.text-xs.text-sky-600 {{ useLocaleTimeAgo(value) }}
                  small.dir-ltr.text-center.text-xs {{ $d(value, 'long') }}
              template(#item.title="{ item, value }")
                p-link(
                  :href="route('list-items.index', item['id'])",
                  as="button",
                  class="hover:underline",
                  type="button",
                  v-text="value"
                ).text-sky-500
              template(#item.user_id="{ item }")
                | {{ item['author'].name }}
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
MyContactsDialog(
  :show="contactsDialog",
  @close="initOrDestroyToSend()",
  @selected-user="onSelectedUser"
)
</template>
