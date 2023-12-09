<script setup lang="ts">
  import ListItemFragment from './Partials/ListItemFragment.vue'
  import { useForm } from 'laravel-precognition-vue'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  const { setBreadCrumbItem } = useGlobalState()

  interface IProps {
    items: any[]
    // eslint-disable-next-line vue/prop-name-casing
    list_case: IListCaseItem
  }

  const props = defineProps<IProps>()

  const Items = ref<IListItem[]>(props.items)

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
      method: form.id ? 'patch' : 'post',
      url: form.id
        ? route('list-items.update', form.id)
        : route('list-items.store'),
      onSuccess(response) {
        toast(response.data.msg, { type: 'success' })
        Items.value.unshift(response.data.item)
        form.reset()
      },
    })

  onMounted(() => {
    setBreadCrumbItem({ title: props.list_case.title })
  })

  const { arrivedState } = useScroll(window)

  function initToUpdateItem(item: any) {
    let dataItem = { ...item }
    dataItem.chamfer = useJsonParser(item.chamfer)
    dataItem.gazor_hinge = useJsonParser(item.gazor_hinge)
    dataItem.pvc = useJsonParser(item.pvc)
    dataItem.groove = useJsonParser(item.groove)
    dataItem.dimensions = useJsonParser(item.dimensions)
    form.setData(dataItem as IListItem)
  }

  const reset = () => {
    form.reset()
    form.id = undefined
  }

  const deleteDialog = ref<boolean>(false)
  const itemId = ref<number>(0)

  function initOrDestroyDeleteItem(id: number = 0) {
    deleteDialog.value = id !== 0
    itemId.value = id
  }

  const doDelete = () =>
    useDelete('list-items.destroy', itemId.value).then(() =>
      initOrDestroyDeleteItem(),
    )
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
    .flex.w-full.flex-col.items-center.gap-y-2
      #list-item-provider(
        :class="[arrivedState.top ? 'lg:top-16' : 'lg:top-6']",
        v-if="list_case.author_id === $page.props.auth['user'].id && !list_case.user_id"
      ).items-top.max-h-44
        ListItemFragment(
          ::chamfer="form.chamfer",
          ::description="form.description",
          ::dimensions="form.dimensions",
          ::gazor_hinge="form.gazor_hinge",
          ::groove="form.groove",
          ::pvc="form.pvc",
          ::qty="form.qty",
          :btn-text="$t('submit-store')",
          :errors="form.errors",
          @btn:click="submit",
          @clear:click="reset",
          btn-color="primary",
          clearable
        )
      div(
        :class="['dark:bg-slate-800 lg:w-[98%]', Object.entries(form.errors).length ? 'mt-40' : 'mt-34']"
      ).w-full.rounded-lg.bg-gray-300.px-2.py-6.shadow-md
        .flex.flex-col.gap-y-2
          div(
            :key="item.id",
            class="hover:bg-gray-400 dark:hover:bg-white dark:hover:bg-opacity-20",
            v-for="item in Items"
          ).items-top.flex.w-full.flex-row.gap-x-2.rounded-lg.p-2
            ListItemFragment(
              :btn-disabled="form.id && form.id === item.id ? true : false",
              :btn-text="$t('edit')",
              :chamfer="item.chamfer",
              :clearable-text="$t('delete', { name: $t('part') })",
              :description="item.description",
              :dimensions="item.dimensions",
              :gazor_hinge="item.gazor_hinge",
              :groove="item.groove",
              :pvc="item.pvc",
              :qty="item.qty",
              @btn:click="initToUpdateItem(item)",
              @clear:click="initOrDestroyDeleteItem(item.id)",
              btn-color="secondary",
              clearable,
              readonly
            )
ConfirmationModal(
  :show="deleteDialog",
  @close="initOrDestroyDeleteItem()",
  @yes="doDelete"
)
  template(#title) {{ $t('delete', { name: $t('part') }) }}
  template(#content) {{ $t('delete-confirmation', { name: $t('part') }) }}
</template>

<style scoped>
  #list-item-provider {
    @apply dark:bg-dark-200 dark:shadow-gray-800 lg:fixed lg:w-[78%] lg:flex-row [&_input]:text-xs;
    @apply z-40 flex flex-col gap-x-2 gap-y-2 rounded-lg bg-sky-300 p-6 shadow-md transition duration-300 lg:flex-row;
  }
</style>
