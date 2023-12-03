<script setup lang="ts">
  import { TransitionFade } from '@morev/vue-transitions'
  import { useTheme } from 'vuetify/lib/framework.mjs'

  useTheme().global.name.value = useColorMode().value

  const { direction } = useGlobalState()

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
