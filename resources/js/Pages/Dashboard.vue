<!-- eslint-disable vue/prop-name-casing -->
<script setup lang="ts">
  import {
    mdiChevronLeft,
    mdiFormatListText,
    mdiInboxArrowDownOutline,
    mdiPageNextOutline,
  } from '@mdi/js'
  import CountUp from 'vue-countup-v3'
  import route from 'ziggy-js'

  interface IProps {
    counter: number[]
    inbox: IListCaseItem[]
    list_cases: IListCaseItem[]
  }

  defineProps<IProps>()

  const { t } = useI18n()

  const counterCards = [
    {
      color: '#4c1d95',
      title: t('inbox-lists'),
      icon: mdiInboxArrowDownOutline,
      url: route('list-case.index', { _query: { type: 'inbox' } }),
    },
    {
      color: '#78350f',
      title: t('menuDrawerItems.my-lists'),
      icon: mdiFormatListText,
      url: route('list-case.index', { _query: { type: 'my-lists' } }),
    },
    {
      color: '#166534',
      title: t('menuDrawerItems.my-invoices'),
      icon: mdiPageNextOutline,
      url: route('invoices.index'),
    },
  ]

  const currentTime = useNow()

  const headers = [
    { key: 'user_id', title: t('creator') },
    { key: 'list_items_count', title: t('parts') },
    { key: 'title', title: t('title') },
    { key: 'created_at', title: t('created_at') },
  ]
</script>

<template lang="pug">
p-head(:title="$t('dashboard')")
PanelLayout
  .container
    v-row
      v-col(cols="12", md="3", v-for="(item, index) in counter")
        v-card(
          :style="`background-color: ${counterCards[index].color} !important;`",
          class="hover:shadow-gray-800"
        ).rounded-xl.shadow-md
          v-card-text.relative
            .flex.flex-col
              .flex.flex-row.items-center
                span {{ counterCards[index].title }}
                p-link(
                  :href="counterCards[index].url",
                  as="button",
                  class="ltr:text-right rtl:text-left",
                  type="button"
                ).grow
                  v-btn(
                    :append-icon="mdiChevronLeft",
                    class="hover:opacity-100",
                    color="white",
                    rounded="full",
                    size="x-small"
                  ).opacity-75.transition.duration-300 {{ $t('show') }}
              CountUp(
                :end-val="item",
                class="ltr:right-5 rtl:left-5"
              ).absolute.bottom-5.text-3xl.font-bold
            v-icon(size="75").mt-12.opacity-40 {{ counterCards[index].icon }}
      v-col(cols="12", md="3")
        .items-top.flex.flex-row
          div(class="child:dark:text-white").flex.flex-col
            h6 {{ eventDay.title }} ;
            small.opacity-75 {{ $d(currentTime, 'long').toString().substring(0, 10) }}
            small.text-center.opacity-75 {{ $d(currentTime, { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}
          v-img(
            :src="`/assets/img/icons/dailyStatus/${eventDay.event}.svg`",
            class="w-[120px] lg:-mt-4 lg:w-auto"
          )
      v-col(cols="12", md="6")
        v-card
          v-card-title
            v-icon(class="ltr:mr-2 rtl:ml-2", size="20") {{ mdiFormatListText }}
            | {{ $t('menuDrawerItems.my-lists') }}
          v-card-text
            v-data-table(
              :headers="headers.slice(1)",
              :items="list_cases",
              :items-per-page-options="[ { title: '5', value: 5 }, { title: '10', value: 10 }, ]",
              items-per-page="5"
            )
              template(#item.list_items_count="{ value }")
                v-chip(
                  v-text="value"
                ).bg-sky-600.bg-opacity-30.text-sky-600.backdrop-blur-sm
              template(#item.created_at="{ value }")
                .flex.flex-col.items-center.gap-y-2
                  small.text-xs.text-sky-600 {{ useLocaleTimeAgo(value) }}
                  small.dir-ltr.text-center.text-xs {{ $d(value, 'long') }}
              template(#item.title="{ item, value }")
                p-link(
                  :href="route('list-items.index', item['slug'])",
                  as="button",
                  class="hover:underline",
                  type="button",
                  v-text="value"
                ).text-sky-500
              template(#item.user_id="{ item }")
                | {{ item['author'].name }}
      v-col(cols="12", md="6")
        v-card
          v-card-title
            v-icon(class="ltr:mr-2 rtl:ml-2", size="20") {{ mdiInboxArrowDownOutline }}
            | {{ $t('inbox-lists') }}
          v-card-text
            v-data-table(
              :headers="headers",
              :items="inbox",
              :items-per-page-options="[ { title: '5', value: 5 }, { title: '10', value: 10 }, ]",
              items-per-page="5"
            )
              template(#item.list_items_count="{ value }")
                v-chip(
                  v-text="value"
                ).bg-sky-600.bg-opacity-30.text-sky-600.backdrop-blur-sm
              template(#item.created_at="{ value }")
                .flex.flex-col.items-center.gap-y-2
                  small.text-xs.text-sky-600 {{ useLocaleTimeAgo(value) }}
                  small.dir-ltr.text-center.text-xs {{ $d(value, 'long') }}
              template(#item.title="{ item, value }")
                p-link(
                  :href="route('list-items.index', item['slug'])",
                  as="button",
                  class="hover:underline",
                  type="button",
                  v-text="value"
                ).text-sky-500
              template(#item.user_id="{ item }")
                | {{ item['author'].name }}
</template>
