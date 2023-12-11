<script setup lang="ts">
  const { t } = useI18n()

  const tab = ref<number>(0)
  const tabs = ref([])
</script>

<template lang="pug">
p-head(:title="$t('settings')")
PanelLayout
  .container
</template>
