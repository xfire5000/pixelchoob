<script setup lang="ts">
  import {
    mdiCheck,
    mdiDownload,
    mdiHammerWrench,
    mdiShapeOutline,
  } from '@mdi/js'
  import { useDisplay } from 'vuetify'
  import route from 'ziggy-js'

  const { mobile } = useDisplay()
</script>

<template lang="pug">
p-head(:title="$t('pixel-choob') + '&nbsp;' + $t('home-page')")
AppLayout
  .container.relative
    #circle-anim
    #pip-anim
    .grid.grid-cols-2.gap-2.py-12.backdrop-blur-md
      div(class="lg:col-span-1").relative.col-span-full.flex.h-full.flex-col.items-center
        div(class="lg:w-2/3").m-auto.w-full
          v-img(:src="`/assets/img/pics/pixel-workout.png`", class="[&_img]:rounded-xl")
        .float-el1
          .flex.w-full.flex-row.items-center.justify-start
            v-icon(size="35").-mt-1.opacity-80 {{ mdiShapeOutline }}
            .px-12
        .float-el2
          .flex.w-full.flex-row.items-center.justify-start
            v-icon(size="35").-mt-12.opacity-80 {{ mdiDownload }}
            .py-8
      div(class="lg:col-span-1").col-span-full
        div(:class="['lg:px-16 lg:py-30', { 'py-10': mobile }]")
          h1(:class="[{ 'text-3xl': mobile }]")
            | {{ $t('pixel-hero') }}&nbsp;
            span(class="bg-300%")
              | {{ $t('with', { name: $t('pixel-choob') }) }}
          h6.mt-6.opacity-50 {{ $t('pixel-hero-desc') }}
          p-link(
            :href="$page.props.auth['user'] ? route('dashboard') : route('login')",
            as="button",
            type="button"
          )
            v-btn(
              class="hover:shadow-primary-outline hover:shadow-cyan-300",
              rounded="lg",
              size="x-large"
            ).mt-2.bg-cyan-400
              v-icon(class="ltr:mr-2 rtl:ml-2", v-if="!$page.props.auth['user']") {{ mdiHammerWrench }}
              v-avatar(
                :image="$page.props.auth['user'].profile_photo_url",
                class="ltr:-ml-2 ltr:mr-2 rtl:-mr-2 rtl:ml-2",
                size="small",
                v-else
              )
              | {{ $page.props.auth['user'] ? $t('go-dashboard') : $t('start-to-work') }}
    .rounded-xl.bg-blue-600.bg-opacity-40.py-4
      div(class="child:rounded-xl").mx-4.grid.grid-cols-4.gap-4
        div(class="dark:bg-dark-100 lg:col-span-1").col-span-full.bg-white.p-2.shadow-sm
          .flex.flex-row.items-center.justify-start.gap-x-2
            div(class="rtl:rounded-bl-0").rounded-md.bg-cyan-600.p-1.text-white
              v-icon {{ mdiCheck }}
            .truncate.text-lg aaaa
</template>

<style scoped>
  #circle-anim {
    @apply absolute right-14 top-24 animate-spin rounded-full bg-gradient-to-tr from-primary to-blue-600 p-20;
  }
  #pip-anim {
    @apply absolute left-14 top-85 rotate-45 animate-pulse rounded-full bg-gradient-to-tr from-orange-500 to-yellow-400 px-20 py-4;
  }
  h1 span {
    @apply animate-gradient bg-gradient-to-r from-sky-600 to-primary bg-clip-text text-transparent;
  }
  .float-el1 {
    @apply absolute -right-3 top-3/4 rounded-full rounded-tl-0 bg-gradient-to-tr from-blue-300 to-pink-200 px-6 py-3 text-blue-600 shadow-md dark:bg-dark-200 xl:right-20 xl:top-2/3;
  }
  .float-el2 {
    @apply absolute -left-2 top-2 rounded-br-full bg-green-600 px-3 py-6 text-white shadow-md dark:bg-dark-200 xl:left-24 xl:top-20;
  }
</style>
