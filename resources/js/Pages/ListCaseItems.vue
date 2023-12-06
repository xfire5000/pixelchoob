<script setup lang="ts">
  import { fourSections, twoSections } from '@Components/ListItemSections'
  import { mdiArrowLeftRight, mdiText } from '@mdi/js'
  import { useForm } from 'laravel-precognition-vue'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  interface IProps {
    items: any[]
    // eslint-disable-next-line vue/prop-name-casing
    list_case: IListCaseItem
  }

  const props = defineProps<IProps>()

  const Items = ref<IListItem[]>(
    props.items.map((item) => {
      let data = item
      if (!_.isObject) {
        data.chamfer = JSON.parse(item.chamfer)
        data.groove = JSON.parse(item.groove)
        data.gazor_hinge = JSON.parse(item.gazor_hinge)
        data.pvc = JSON.parse(item.pvc)
        data.dimensions = JSON.parse(item.dimensions)
      }
      return data
    }),
  )

  const form = useForm('post', route('list-items.store'), {
    description: '',
    chamfer: { l1: false, l2: false, w1: false, w2: false },
    gazor_hinge: { l: false, w: false },
    groove: { l: false, w: false },
    pvc: { l1: false, l2: false, w1: false, w2: false },
    qty: 1,
    dimensions: { w: 0, h: 0 },
  } as IListItem)

  const submit = () =>
    form.submit({
      onSuccess(response) {
        toast(response.data.msg, { type: 'success' })
        Items.value.unshift(response.data.item)
        form.reset()
      },
    })
</script>

<template lang="pug">
p-head(:title="$t('list', { name: list_case.title })")
PanelLayout
  .container.relative
    div(
      v-if="list_case.author_id !== $page.props.auth['user'].id"
    ).my-4.grid.grid-cols-3.gap-2
      strong(class="lg:col-span-1").col-span-full {{ $t('customer-name') }}: {{ list_case.author.name }}
      strong(class="lg:col-span-1").col-span-full.flex.flex-row.gap-x-2
        | {{ $t('phone-number') }}:&nbsp;
        v-chip(
          :key="item.id",
          v-for="item in list_case.author.addressInfos.filter((f) => ['phone', 'mobile'].includes(f.type))",
          v-text="item.description"
        )
      strong(class="lg:col-span-1").col-span-full {{ $t('date') }}: {{ $d(list_case.created_at, 'long') }}
    .flex.max-w-screen-xl.flex-col.items-center.gap-y-2
      div(
        class="dark:bg-dark-200 dark:shadow-gray-800 lg:fixed lg:top-6 lg:w-[78%] lg:flex-row [&_input]:text-xs",
        v-if="list_case.author_id === $page.props.auth['user'].id && !list_case.user_id"
      ).items-top.max-h-38.z-40.flex.max-w-screen-xl.flex-col.gap-x-2.gap-y-2.rounded-lg.bg-sky-300.p-6.shadow-md
        v-textarea(
          ::="form.description",
          :label="$t('description')",
          :prepend-inner-icon="mdiText",
          hide-details="auto",
          max-rows="2",
          rows="1",
          variant="outlined"
        )
        fourSections(
          ::l1="form.chamfer.l1",
          ::l2="form.chamfer.l2",
          ::w1="form.chamfer.w1",
          ::w2="form.chamfer.w2",
          :title="$t('chamfer')"
        )
        twoSections(
          ::l1="form.gazor_hinge.l",
          ::l2="form.gazor_hinge.w",
          :title="$t('gazor_hinge')"
        )
        twoSections(
          ::l1="form.groove.l",
          ::l2="form.groove.w",
          :title="$t('groove')"
        )
        fourSections(
          ::l1="form.pvc.l1",
          ::l2="form.pvc.l2",
          ::w1="form.pvc.w1",
          ::w2="form.pvc.w2",
          :title="$t('pvc-settings')"
        )
        .mt-3.flex.h-full.grow.flex-col.gap-y-2
          .items-top.flex.flex-row.gap-x-2
            v-text-field(
              ::="form.qty",
              :label="$t('qty')",
              hide-details="auto",
              type="number"
            )
            v-text-field(
              ::="form.dimensions.w",
              :label="$t('sizes.width')",
              :prepend-inner-icon="mdiArrowLeftRight",
              hide-details="auto",
              type="number"
            )
            v-text-field(
              ::="form.dimensions.h",
              :label="$t('sizes.height')",
              :prepend-inner-icon="mdiArrowLeftRight",
              class="[&_.v-icon]:rotate-90",
              hide-details="auto",
              type="number"
            )
          .max-h-10
            v-btn(@click="submit", block, color="primary") {{ $t('submit-store') }}
      div(
        class="dark:bg-slate-800 lg:w-[98%]"
      ).mt-34.w-full.rounded-lg.bg-gray-300.px-2.py-6.shadow-md
        .flex.flex-col.gap-y-2
          div(
            :key="item.id",
            class="hover:bg-gray-400 dark:hover:bg-white dark:hover:bg-opacity-20",
            v-for="item in Items"
          ).items-top.flex.w-full.flex-row.gap-x-2.rounded-lg.p-2
            v-textarea(
              ::="item.description",
              :label="$t('description')",
              :prepend-inner-icon="mdiText",
              hide-details="auto",
              max-rows="2",
              readonly,
              rows="1",
              variant="outlined"
            )
            fourSections(
              ::l1="item.chamfer.l1",
              ::l2="item.chamfer.l2",
              ::w1="item.chamfer.w1",
              ::w2="item.chamfer.w2",
              :disabler="{ l1: true, l2: true, w1: true, w2: true }",
              :title="$t('chamfer')"
            )
            twoSections(
              ::l1="item.gazor_hinge.l",
              ::l2="item.gazor_hinge.w",
              :disabler="{ l: true, w: true }",
              :title="$t('gazor_hinge')"
            )
            twoSections(
              ::l1="item.groove.l",
              ::l2="item.groove.w",
              :disabler="{ l: true, w: true }",
              :title="$t('groove')"
            )
            fourSections(
              ::l1="item.pvc.l1",
              ::l2="item.pvc.l2",
              ::w1="item.pvc.w1",
              ::w2="item.pvc.w2",
              :disabler="{ l1: true, l2: true, w1: true, w2: true }",
              :title="$t('pvc-settings')"
            )
            .flex.h-full.grow.flex-col.gap-y-2
              .items-top.flex.max-h-10.flex-row.gap-x-2
                v-text-field(
                  ::="item.qty",
                  :label="$t('qty')",
                  hide-details="auto",
                  readonly,
                  type="number"
                )
                v-text-field(
                  ::="item.dimensions.w",
                  :label="$t('sizes.width')",
                  :prepend-inner-icon="mdiArrowLeftRight",
                  hide-details="auto",
                  readonly,
                  type="number"
                )
                v-text-field(
                  ::="item.dimensions.h",
                  :label="$t('sizes.height')",
                  :prepend-inner-icon="mdiArrowLeftRight",
                  class="[&_.v-icon]:rotate-90",
                  hide-details="auto",
                  readonly,
                  type="number"
                )
              .max-h-10
                v-btn(@click="submit", block, color="secondary") {{ $t('edit') }}
</template>
