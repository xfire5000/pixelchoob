<script setup lang="ts">
  import { usePage } from '@inertiajs/vue3'
  import { TransitionFade } from '@morev/vue-transitions'
  import AOS from 'aos'
  import { useTheme } from 'vuetify/lib/framework.mjs'

  const cursorOptions = CursorFollowerOptions

  useTheme().global.name.value = useColorMode().value

  const { clearNavItems, direction } = useGlobalState()

  const isPanel = usePage().component.includes('Panel')

  onMounted(() => {
    let classNames = document.body.className
    if (!isPanel) {
      AOS.init()
      if (!classNames.includes('app'))
        JQuery('body').removeClass('panel').addClass('app')
    } else {
      if (!classNames.includes('panel'))
        JQuery('body').removeClass('app').addClass('panel')
    }
  })

  onBeforeMount(() => clearNavItems)

  useFavicon(`/assets/img/${!isPanel ? 'orise.png' : 'orise-dashboard.png'}`)

  useTextDirection().value = direction.value
</script>

<template lang="pug">
o-head
  meta(name="darkreader-lock")
  meta(content="#01DC84", name="theme-color")
  link(:href="$page.props.ziggy['url']", rel="canonical")
transition-fade
  slot
</template>

<style>
  .app {
    @apply text-center dark:bg-dark-100;
  }
  .panel {
    @apply ltr:text-left rtl:text-right dark:bg-dark-300;
  }
</style>
