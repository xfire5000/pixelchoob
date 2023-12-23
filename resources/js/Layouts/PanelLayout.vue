<script setup lang="ts">
  import { BreadCrumb, NavDrawer } from '@Components/PanelLayoutComponents'
</script>

<template lang="pug">
.flex.h-full.w-full.flex-col
  .flex.flex-1
    NavDrawer
    main.my-6.flex-1.overflow-hidden
      .container.-mt-4
        BreadCrumb
      slot
</template>
