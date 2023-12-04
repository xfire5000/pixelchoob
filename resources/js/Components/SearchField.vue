<script setup lang="ts">
  import { mdiArrowLeft, mdiMagnify } from '@mdi/js'

  const props = withDefaults(defineProps<{ modelValue: string }>(), {
    modelValue: () => '',
  })

  const emit = defineEmits(['update:modelValue', 'doSearch'])

  const value = useVModel(props, 'modelValue', emit)
</script>

<template lang="pug">
v-text-field(
  ::="value",
  :append-inner-icon="mdiArrowLeft",
  :placeholder="$t('search-placeholder')",
  :prepend-inner-icon="mdiMagnify",
  @click:append-inner="emit('doSearch')",
  @keypress.enter="emit('doSearch')",
  hide-details="auto",
  variant="outlined"
)
</template>
