<script setup lang="ts">
  import { mdiArrowRight, mdiPlusCircleOutline } from '@mdi/js'
  import route from 'ziggy-js'

  const { t } = useI18n()

  const headers = [
    { key: 'user_id', title: t('creator') },
    { key: 'title', title: t('title') },
    { key: 'description', title: t('description') },
    { key: 'pvc', title: t('pvc-infos') },
    { key: 'stock', title: t('stock-infos') },
    { key: 'updated_at', title: t('updated_at') },
  ]

  const searched = useUrlSearchParams('history').s

  const tab = ref<number>(0)
  const tabs = ref([
    {
      title: t('menuDrawerItems.my-lists'),
      items: [],
      headers: headers.slice(1),
    },
    { title: t('inbox-lists'), items: [], headers: headers },
    { title: t('archived'), items: [], headers: headers.slice(1) },
  ])

  const search = ref<string>(searched ? (searched as string) : '')

  const doSearch = () => useSearch('list-cases.index', search.value as string)

  const selectedItems = ref<number[]>([])

  const case_dialog = ref<boolean>(false)
  const case_item = ref<IListCaseItem>(undefined)

  const listCaseDialogActivator = (item?: IListCaseItem) => {
    case_item.value = item
    case_dialog.value = true
  }
</script>

<template lang="pug">
p-head(:title="$t('menuDrawerItems.my-lists')")
PanelLayout
  .container
    v-row
      v-col(cols="12", md="8")
        | {{ $t('menuDrawerItems.my-lists') }}
        SearchField(::="search", @do-search="doSearch", class="w-1/3").mt-4
      v-col(class="rtl:text-left", cols="12", md="4")
        v-btn(
          :prepend-icon="mdiArrowRight",
          rounded="lg",
          v-show="searched",
          variant="outlined"
        ).my-auto.ml-3 {{ $t('back') }}
        v-btn(
          :disabled="!selectedItems.length",
          :prepend-icon="mdiArrowRight",
          color="red",
          rounded="lg",
          variant="tonal"
        ).my-auto.ml-3 {{ $t('delete') }}
        v-btn(
          :prepend-icon="mdiPlusCircleOutline",
          @click="listCaseDialogActivator",
          color="secondary",
          rounded="lg"
        ).my-auto {{ $t('add', { name: $t('list') }) }}
      v-col(cols="12").flex.flex-col.gap-y-2
        v-tabs(::="tab", color="secondary")
          v-tab(v-for="item in tabs") {{ item.title }}
        v-window(::="tab")
          v-window-item(:value="index", v-for="(item, index) in tabs")
            v-data-table(
              ::="selectedItems",
              :headers="item.headers",
              :items="item.items",
              item-value="id",
              show-select
            )
              template(#item.updated_at="{ value }")
                | {{ useLocaleTimeAgo(value) }}
              template(#item.title="item")
                p-link(
                  :href="route('list-items.index', { _query: { list_case_id: item['id'] } })",
                  class="hover:underline",
                  v-text="item['title']"
                ).text-sky-500
              template(#item.user_id="item")
                | {{ item['author'].name }}
ListCaseDialog(
  :initial-form="case_item",
  :show="case_dialog",
  @close="case_dialog = false"
)
</template>
