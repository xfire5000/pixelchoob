<script setup lang="ts">
  import DialogModal from '../DialogModal.vue'
  import { mdiAlertOutline, mdiCheck, mdiReceiptText, mdiText } from '@mdi/js'
  import { useForm } from 'laravel-precognition-vue'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  interface IProps {
    show: boolean
    listItems: IListItem[]
    listCase: IListCaseItem
    settings?: InvoicePriceSetting
  }

  const props = withDefaults(defineProps<IProps>(), {
    show: false,
    settings: () => ({
      cutting: 0,
      pvc: {
        size_1: 0,
        size_2: 0,
      },
      chamfer: 0,
      groove: 0,
    }),
  })

  const emit = defineEmits(['close', 'submitted'])

  const form = useForm(
    'post',
    route('invoices.store'),
    !props.listCase.invoice
      ? {
          list_case_id: props.listCase.id,
          description: '',
          ...props.settings,
          sumPVC: 0,
          sumCutting: 0,
          countParts: 0,
          sumGroove: 0,
          sumChamfers: 0,
        }
      : { ...props.listCase.invoice },
  )

  onUpdated(() => {
    if (props.listCase.invoice) form.pvc = useJsonParser(form.pvc)
  })

  const pvcSize = useJsonParser(props.listCase.pvc).size

  const calcItems = computed(() => {
    if (!props.listCase.invoice) {
      let sumChamfers: number = 0,
        sumGroove: number = 0,
        countParts: number = 0,
        sumCutting: number = 0,
        sumPVC: number = 0
      props.listItems.forEach((item) => {
        let dataItem = useInitListItem(item)
        countParts += dataItem.qty
        let newChamfer = 0,
          newPVC = 0
        // chamfers
        if (dataItem.chamfer.l1 && dataItem.chamfer.l2) {
          newChamfer += dataItem.dimensions.h * 2
        } else if (dataItem.chamfer.l1 || dataItem.chamfer.l2) {
          newChamfer += dataItem.dimensions.h
        }
        if (dataItem.chamfer.w1 && dataItem.chamfer.w2) {
          newChamfer += dataItem.dimensions.w * 2
        } else if (dataItem.chamfer.w1 || dataItem.chamfer.w2) {
          newChamfer += dataItem.dimensions.w
        }
        sumChamfers += newChamfer * dataItem.qty
        // groove
        if (dataItem.groove.l) sumGroove += dataItem.dimensions.h
        else if (dataItem.groove.w) sumGroove += dataItem.dimensions.w
        // pvc
        if (dataItem.chamfer.l1 && dataItem.chamfer.l2) {
          newPVC += dataItem.dimensions.h * 2
        } else if (dataItem.chamfer.l1 || dataItem.chamfer.l2) {
          newPVC += dataItem.dimensions.h
        }
        if (dataItem.chamfer.w1 && dataItem.chamfer.w2) {
          newPVC += dataItem.dimensions.w * 2
        } else if (dataItem.chamfer.w1 || dataItem.chamfer.w2) {
          newPVC += dataItem.dimensions.w
        }
        sumPVC += newPVC * dataItem.qty
      })
      sumChamfers *= form.chamfer
      sumCutting = countParts * form.cutting
      sumPVC *= pvcSize === '1' ? form.pvc.size_1 : form.pvc.size_2
      sumGroove *= form.groove
      return {
        sumChamfers,
        sumGroove,
        countParts,
        sumCutting,
        sumPVC,
      }
    } else return props.listCase.invoice
  })

  const submit = () => {
    form.setData({
      sumChamfers: calcItems.value.sumChamfers,
      sumGroove: calcItems.value.sumGroove,
      countParts: calcItems.value.countParts,
      sumCutting: calcItems.value.sumCutting,
      sumPVC: calcItems.value.sumPVC,
    })
    form.submit({
      onSuccess(res) {
        toast(res.data.msg, { type: 'success' })
        emit('submitted', res.data.item)
      },
    })
  }
</script>

<template lang="pug">
DialogModal(:show, @close="emit('close')")
  template(#title)
    v-icon(class="ltr:mr-2 rtl:ml-2", size="small") {{ mdiReceiptText }}
    | {{ $t('invoicing') }}
  template(#content)
    .container
      v-alert(
        :icon="mdiAlertOutline",
        :text="$t('invoice-dialog-hint')",
        closable,
        color="warning",
        v-if="!listCase.invoice",
        variant="tonal"
      )
      v-table(fixed-header, hover).max-h-64.overflow-y-scroll
        thead
          tr
            th {{ $t('title') }}
            th {{ $t('price') }}
            th {{ $t('price_total') }}
        tbody(class="[&_input]:text-xs")
          tr
            td
              | {{ $t('cutting') }}
              v-chip(size="x-small").bg-sky-600.bg-opacity-20.text-sky-600 {{ calcItems.countParts }} {{ $t('part') }}
            td
              v-text-field(
                ::="form.cutting",
                :readonly="!!listCase.invoice",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td
              | {{ $n(calcItems.sumCutting) }}
              small.mr-1.opacity-75 {{ $t('currency') }}
          tr(v-if="pvcSize === '1'")
            td {{ $t('pvc-size.1mm') }} {{ $t('pvc') }}
            td
              v-text-field(
                ::="form.pvc.size_1",
                :readonly="!!listCase.invoice",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td
              | {{ $n(calcItems.sumPVC) }}
              small.mr-1.opacity-75 {{ $t('currency') }}
          tr(v-else)
            td {{ $t('pvc-size.2mm') }} {{ $t('pvc') }}
            td
              v-text-field(
                ::="form.pvc.size_2",
                :readonly="!!listCase.invoice",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td
              | {{ $n(calcItems.sumPVC) }}
              small.mr-1.opacity-75 {{ $t('currency') }}
          tr
            td {{ $t('chamfer') }}
            td
              v-text-field(
                ::="form.chamfer",
                :readonly="!!listCase.invoice",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td
              | {{ $n(calcItems.sumChamfers) }}
              small.mr-1.opacity-75 {{ $t('currency') }}
          tr
            td {{ $t('groove') }}
            td
              v-text-field(
                ::="form.groove",
                :readonly="!!listCase.invoice",
                :suffix="$t('currency')",
                hide-details="auto",
                type="number"
              )
            td
              | {{ $n(calcItems.sumGroove) }}
              small.mr-1.opacity-75 {{ $t('currency') }}
      v-divider.mx-2
      v-textarea(
        ::="form.description",
        :label="$t('description')",
        :prepend-inner-icon="mdiText",
        :readonly="!!listCase.invoice",
        hide-details="auto",
        max-rows="2",
        rows="2",
        variant="outlined"
      ).mt-4
  template(#footer)
    v-btn(
      :prepend-icon="mdiCheck",
      @click="submit",
      color="primary",
      rounded="lg"
    ) {{ $t('submit-store') }}
</template>

<style scoped>
  tr > td {
    @apply child:my-3;
  }
</style>
