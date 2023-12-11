<script setup lang="ts">
  import DialogModal from '../DialogModal.vue'
  import { mdiReceiptText, mdiText } from '@mdi/js'
  import { useForm } from 'laravel-precognition-vue'
  import route from 'ziggy-js'

  interface IProps {
    show: boolean
    listItems: IListItem[]
    listCase: IListCaseItem
  }

  const props = withDefaults(defineProps<IProps>(), { show: false })

  const emit = defineEmits(['close'])

  const calcForm = reactive({
    cutting: 0,
    pvc: {
      size_1: 0,
      size_2: 0,
    },
    chamfer: 0,
    groove: 0,
  })

  const calcItems = computed(() => {
    let sumChamfers: number = 0,
      countChamfers: number = 0,
      sumGroove: number = 0,
      countParts: number = 0,
      sumCutting: number = 0
    props.listItems.forEach((item) => {
      let dataItem = useInitListItem(item)
      countParts += dataItem.qty
      // let newChamfer = 0
      // chamfers
      if (dataItem.chamfer.l1 && dataItem.chamfer.l2) {
        // newChamfer += dataItem.dimensions.h * 2
        countChamfers += 2
      } else if (dataItem.chamfer.l1 || dataItem.chamfer.l2) {
        // newChamfer += dataItem.dimensions.h
        countChamfers += 1
      }
      if (dataItem.chamfer.w1 && dataItem.chamfer.w2) {
        // newChamfer += dataItem.dimensions.w * 2
        countChamfers += 2
      } else if (dataItem.chamfer.w1 || dataItem.chamfer.w2) {
        // newChamfer += dataItem.dimensions.w
        countChamfers += 1
      }
      sumChamfers += /*newChamfer **/ dataItem.qty
      // groove
      if (dataItem.groove.l) sumGroove += dataItem.dimensions.h
      else if (dataItem.groove.w) sumGroove += dataItem.dimensions.w
      // pvc
    })
    sumChamfers = sumChamfers * calcForm.chamfer
    sumCutting = countParts * calcForm.cutting
    return { sumChamfers, countChamfers, sumGroove, countParts, sumCutting }
  })

  const form = useForm('post', route('invoices.store'), {
    description: '',
  })
</script>

<template lang="pug">
DialogModal(:show, @close="emit('close')")
  template(#title)
    v-icon(class="ltr:mr-2 rtl:ml-2", size="small") {{ mdiReceiptText }}
    | {{ $t('invoicing') }}
  template(#content)
    .container
      v-table(fixed-header, hover).max-h-64.overflow-y-scroll
        thead
          tr
            th {{ $t('title') }}
            th {{ $t('qty') }}
            th {{ $t('price') }}
            th {{ $t('price_total') }}
        tbody(class="[&_input]:text-xs")
          tr
            td {{ $t('cutting') }}
            td
              v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countParts }} {{ $t('part') }}
            td
              v-text-field(
                ::="calcForm.cutting",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td(v-text="$n(calcItems.sumCutting)")
          tr
            td {{ $t('pvc-size.1mm') }} {{ $t('pvc') }}
            td
              v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countParts }}
            td
              v-text-field(
                ::="calcForm.pvc.size_1",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
          tr
            td {{ $t('pvc-size.2mm') }} {{ $t('pvc') }}
            td
              v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countParts }}
            td
              v-text-field(
                ::="calcForm.pvc.size_2",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
          tr
            td {{ $t('chamfer') }}
            td
              .flex.flex-row.items-center.gap-x-2
                v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countChamfers }} {{ $t('type-of', { name: $t('cutting') }) }}
                span X
                v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countParts }} {{ $t('part') }}
            td
              v-text-field(
                ::="calcForm.chamfer",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td(v-text="$n(calcItems.sumChamfers)")
          tr
            td {{ $t('groove') }}
            td
              v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countParts }}
            td
              v-text-field(
                ::="calcForm.groove",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
      v-divider.mx-2
      v-textarea(
        ::="form.description",
        :label="$t('description')",
        :prepend-inner-icon="mdiText",
        hide-details="auto",
        max-rows="2",
        rows="2",
        variant="outlined"
      ).mt-4
  template(#footer)
    v-btn(color="primary", rounded="lg") {{ $t('submit-store') }}
</template>

<style scoped>
  tr > td {
    @apply child:my-3;
  }
</style>
