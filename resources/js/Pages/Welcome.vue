<script setup lang="ts"></script>

<template lang="pug">
p-head(:title="$t('home-page')")
AppLayout
  .container-fluid.-mt-8
    .cols-12.grid.grid.gap-4
      .col-span-full
      img(:src="`/assets/img/pics/modern-table-with-papers.jpg`").w-full
</template>
