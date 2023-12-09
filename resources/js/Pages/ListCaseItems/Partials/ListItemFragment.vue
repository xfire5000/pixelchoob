<script setup lang="ts">
  import { fourSections, twoSections } from '@Components/ListItemSections'
  import { mdiArrowLeftRight, mdiText, mdiTrashCanOutline } from '@mdi/js'

  interface IProps {
    btnColor: string
    btnText: string
    readonly?: boolean
    errors?: any
    clearable: boolean
    btnDisabled?: boolean
    clearableText?: string
  }

  withDefaults(defineProps<IProps>(), {
    readonly: false,
    clearable: false,
  })

  const { description, chamfer, gazor_hinge, groove, pvc, qty, dimensions } =
    defineModels<{
      description: string
      chamfer: IFourSections
      gazor_hinge: ITwoSections
      groove: ITwoSections
      pvc: IFourSections
      qty: number
      dimensions: { w: number; h: number }
    }>()

  const emit = defineEmits<{ 'btn:click': any; 'clear:click': any }>()
</script>

<template lang="pug">
v-textarea(
  ::="description",
  :auto-grow="false",
  :label="$t('description')",
  :prepend-inner-icon="mdiText",
  :readonly,
  hide-details="auto",
  max-rows="2",
  rows="1",
  variant="outlined"
)
fourSections(
  ::l1="useJsonParser(chamfer).l1",
  ::l2="useJsonParser(chamfer).l2",
  ::w1="useJsonParser(chamfer).w1",
  ::w2="useJsonParser(chamfer).w2",
  :disabler="readonly ? { l1: true, l2: true, w1: true, w2: true } : { l1: pvc.l1 || groove.l || gazor_hinge.l, l2: pvc.l2 || groove.l || gazor_hinge.l, w1: pvc.w1 || groove.l || gazor_hinge.l, w2: pvc.w2 || groove.l || gazor_hinge.l }",
  :title="$t('chamfer')"
)
twoSections(
  ::l1="useJsonParser(gazor_hinge).l",
  ::l2="useJsonParser(gazor_hinge).w",
  :disabler="readonly ? { l: true, w: true } : { l: groove.l || chamfer.l1 || chamfer.l2 || pvc.l1 || pvc.l2, w: groove.w || chamfer.w1 || chamfer.w2 || pvc.w1 || pvc.w2 }",
  :title="$t('gazor_hinge')"
)
twoSections(
  ::l1="useJsonParser(groove).l",
  ::l2="useJsonParser(groove).w",
  :disabler="readonly ? { l: true, w: true } : { l: gazor_hinge.l || chamfer.l1 || chamfer.l2 || pvc.l1 || pvc.l2, w: gazor_hinge.w || chamfer.w1 || chamfer.w2 || pvc.w1 || pvc.w2 }",
  :title="$t('groove')"
)
fourSections(
  ::l1="useJsonParser(pvc).l1",
  ::l2="useJsonParser(pvc).l2",
  ::w1="useJsonParser(pvc).w1",
  ::w2="useJsonParser(pvc).w2",
  :disabler="readonly ? { l1: true, l2: true, w1: true, w2: true } : { l1: chamfer.l1 || groove.l || gazor_hinge.l, l2: chamfer.l2 || groove.l || gazor_hinge.l, w1: chamfer.w1 || groove.l || gazor_hinge.l, w2: chamfer.w2 || groove.l || gazor_hinge.l }",
  :title="$t('pvc-settings')"
)
.flex.h-full.grow.flex-col.gap-y-2
  .items-top.flex.h-auto.max-h-16.flex-row.gap-x-2
    v-text-field(
      ::="qty",
      :error-messages="errors?.qty",
      :label="$t('qty')",
      :readonly,
      hide-details="auto",
      type="number"
    )
    v-text-field(
      ::="useJsonParser(dimensions).w",
      :error-messages="errors?.['dimensions.w']",
      :label="$t('sizes.width')",
      :prepend-inner-icon="mdiArrowLeftRight",
      :readonly,
      hide-details="auto",
      type="number"
    )
    v-text-field(
      ::="useJsonParser(dimensions).h",
      :error-messages="errors?.['dimensions.h']",
      :label="$t('sizes.height')",
      :prepend-inner-icon="mdiArrowLeftRight",
      :readonly,
      class="[&_.v-icon]:rotate-90",
      hide-details="auto",
      type="number"
    )
  .flex.max-h-10.flex-row.gap-x-2
    v-btn(
      :color="btnColor",
      :disabled="btnDisabled",
      @click="emit('btn:click')"
    ).grow {{ btnText }}
    v-tooltip(location="bottom", v-if="clearable")
      template(#activator="{ props }")
        v-btn(
          :icon="mdiTrashCanOutline",
          @click="emit('clear:click')",
          color="red",
          size="small",
          v-bind="props",
          variant="tonal"
        )
      span {{ clearableText && clearableText.length ? clearableText : $t('clear-form') }}
</template>
