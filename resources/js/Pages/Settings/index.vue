<script setup lang="ts">
  import { InvoicesPrice } from './Partials/SettingSections'
  import { mdiCalculatorVariantOutline } from '@mdi/js'

  const { t } = useI18n()

  const tab = ref<number>(0)
  const tabs = [
    {
      title: t('settings.define-invoices-price'),
      icon: mdiCalculatorVariantOutline,
    },
  ]
</script>

<template lang="pug">
p-head(:title="$t('settings.index')")
PanelLayout
  .container
    v-row
      v-col(cols="12", md="8")
        | {{ $t('settings.index') }}
      v-col(cols="12")
        v-tabs(::="tab", color="sky-600")
          v-tab(:value="index", v-for="(item, index) in tabs")
            v-icon(class="ltr:mr-2 rtl:ml-2", size="small") {{ item.icon }}
            | {{ item.title }}
        v-window(::="tab").my-3
          v-window-item(:value="0")
            InvoicesPrice
</template>
