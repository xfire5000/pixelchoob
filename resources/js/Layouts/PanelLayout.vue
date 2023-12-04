<script setup lang="ts">
  import { Header, NavDrawer } from '@Components/PanelLayoutComponents'
</script>

<template lang="pug">
.flex.h-full.w-full.flex-col
  Header
  .flex.flex-1
    NavDrawer
    main.my-6.flex-1.overflow-hidden
      slot
</template>
