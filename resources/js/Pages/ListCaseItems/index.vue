<!-- eslint-disable vue/prop-name-casing -->
<script setup lang="ts">
  import ListItemFragment from './Partials/ListItemFragment.vue'
  import { usePage } from '@inertiajs/vue3'
  import {
    mdiChatOutline,
    mdiCheckAll,
    mdiDotsHorizontal,
    mdiMicrosoftExcel,
    mdiReceiptTextCheck,
    mdiReceiptTextEdit,
    mdiSend,
  } from '@mdi/js'
  import { useForm } from 'laravel-precognition-vue'
  import { mergeProps } from 'vue'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  const { setBreadCrumbItem } = useGlobalState()

  interface IProps {
    items: any[]
    list_case: IListCaseItem
    invoice_prices: string
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
        if (!form.id) Items.value.unshift(response.data.item)
        else {
          let index = Items.value.findIndex((item) => item.id === form.id)
          Items.value[index] = response.data.item
        }
        reset()
      },
    })

  onMounted(() => {
    setBreadCrumbItem({ title: props.list_case.title })
  })

  const { arrivedState } = useScroll(window)

  const initToUpdateItem = (item: any) => form.setData(useInitListItem(item))

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
    useDelete('list-items.destroy', itemId.value).then(() => {
      _.remove(Items.value, (item) => item.id === itemId.value)
      initOrDestroyDeleteItem()
    })

  const contactsDialog = ref<boolean>(false)

  const onSelectedUser = (id: number) =>
    useFetchClient
      .post<{ msg: string }>(route('list-cases.send'), {
        user_id: id,
      })
      .then(({ data }) => {
        contactsDialog.value = false
        toast(data.value.msg, { type: 'success' })
        usePage().props.list_case['user_id'] = id
      })

  const invoiceDialog = ref<boolean>(false)

  function onSubmitted(item: IInvoiceItem) {
    usePage().props.list_case['invoice'] = item
    invoiceDialog.value = false
  }

  const ticketsDialog = ref<boolean>(false)
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
          v-for="item in list_case.author.address_infos.filter((f) => ['phone', 'mobile'].includes(f.type))",
          v-text="item.description"
        )
      strong(class="lg:col-span-1").col-span-full {{ $t('date') }}: {{ $d(list_case.created_at, 'long') }}
    div(v-else).my-4.grid.grid-cols-3.gap-2
      div(class="lg:col-span-1").col-span-full
        small {{ $t('status') }}:
        v-chip(
          :class="[list_case.user_id ? 'bg-green-200 text-primary' : 'bg-indigo-600 text-indigo-500']"
        ).bg-opacity-20 {{ list_case.user_id ? $t('sent') : $t('no-sent') }}
      div(
        class="ltr:text-right rtl:text-left dark:text-blue-400 lg:col-span-2",
        v-show="list_case.viewed === 1"
      ).text-blue-600
        small
          v-icon(class="ltr:mr-2 rtl:ml-2") {{ mdiCheckAll }}
          | {{ $t('viewed') }}
    .flex.w-full.flex-col.items-center.gap-y-2
      #list-item-provider(
        :class="[arrivedState.top ? 'lg:top-26' : 'lg:top-6', 'lg:max-h-44']",
        v-if="list_case.author_id === $page.props.auth['user'].id && !list_case.user_id"
      ).items-top
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
      #list-items-provider(
        :class="[Object.entries(form.errors).length ? 'lg:mt-40' : { 'lg:mt-34': list_case.author_id === $page.props.auth['user'].id && !list_case.user_id }]"
      )
        div(:key="item.id", v-for="(item, index) in Items").items-top
          ListItemFragment(
            :btn-disabled="(form.id && form.id === item.id) || !!list_case.user_id",
            :btn-text="$t('edit', { name: $t('part') })",
            :chamfer="useJsonParser(item.chamfer)",
            :clearable="!list_case.user_id",
            :clearable-text="$t('delete', { name: $t('part') })",
            :description="item.description",
            :dimensions="useJsonParser(item.dimensions)",
            :gazor_hinge="useJsonParser(item.gazor_hinge)",
            :groove="useJsonParser(item.groove)",
            :pvc="useJsonParser(item.pvc)",
            :qty="item.qty",
            @btn:click="initToUpdateItem(item)",
            @clear:click="initOrDestroyDeleteItem(item.id)",
            btn-color="secondary",
            readonly
          )
          v-divider(
            :class="['lg:hidden', { hidden: index > Items.length - 2 }]"
          ).mt-2.block
    v-menu(location="top")
      template(#activator="{ props: menu, isActive: isMenuActive }")
        v-tooltip(location="right")
          template(#activator="{ props: tooltip }")
            .fixed.bottom-5.left-5
              .relative
                div(
                  v-if="list_case.ticket?.new_messages_count > 0"
                ).fixed-badge
                  span {{ list_case.ticket.new_messages_count }}
                v-btn(
                  :class="[{ 'animate-bounce hover:animate-none': !!list_case.user_id }, { 'animate-none': isMenuActive }]",
                  :icon="mdiDotsHorizontal",
                  color="primary",
                  v-bind="mergeProps(menu, tooltip)"
                )
          span {{ $t('actions') }}
      v-list
        v-list-item(
          :prepend-icon="mdiSend",
          @click="contactsDialog = true",
          v-if="list_case.author_id === $page.props.auth['user'].id && !list_case.user_id && !list_case.deleted_at"
        ) {{ $t('send') }}
        v-list-item(
          :prepend-icon="mdiChatOutline",
          @click="ticketsDialog = true",
          v-else
        )
          | {{ $t('tickets') }}
          template(#append)
            v-chip(
              color="sky-600",
              v-if="list_case.ticket && list_case.ticket.new_messages_count > 0"
            ) {{ $n(list_case.ticket.new_messages_count) }}
        v-divider.mx-2
        v-list-item(
          :href="route('list-items.export')",
          :prepend-icon="mdiMicrosoftExcel",
          target="_blank"
        ) {{ $t('export-excel') }}
        v-list-item(
          :prepend-icon="mdiReceiptTextEdit",
          @click="invoiceDialog = true",
          v-if="!list_case.invoice && list_case.author_id !== $page.props.auth['user'].id"
        ) {{ $t('invoicing') }}
        v-list-item(
          :prepend-icon="mdiReceiptTextCheck",
          @click="invoiceDialog = true",
          v-else-if="list_case.invoice"
        ) {{ $t('show', { name: $t('invoice') }) }}
ConfirmationModal(
  :show="deleteDialog",
  @close="initOrDestroyDeleteItem()",
  @yes="doDelete"
)
  template(#title) {{ $t('delete', { name: $t('part') }) }}
  template(#content) {{ $t('delete-confirmation', { name: $t('part') }) }}
MyContactsDialog(
  :show="contactsDialog",
  @close="contactsDialog = false",
  @selected-user="onSelectedUser"
)
ListInvoiceDialog(
  :list-case="list_case",
  :list-items="Items",
  :settings="useJsonParser(invoice_prices)",
  :show="invoiceDialog",
  @close="invoiceDialog = false",
  @submitted="onSubmitted"
)
ListCaseTicketsDialog(
  :list-case-id="list_case.id",
  :show="ticketsDialog",
  @close="ticketsDialog = false"
)
</template>

<style scoped>
  #list-item-provider {
    @apply text-gray-800 dark:bg-dark-200 dark:text-white dark:shadow-gray-800 lg:fixed lg:w-[78%] lg:flex-row [&_input]:text-xs;
    @apply z-40 flex flex-col gap-x-2 gap-y-2 rounded-lg bg-sky-300 p-6 transition duration-300 lg:flex-row lg:shadow-md;
  }
  #list-items-provider {
    @apply flex w-full flex-col gap-y-2 rounded-lg bg-gray-200 px-2 py-4 text-gray-800 shadow-md dark:bg-slate-800 dark:text-white lg:w-[98%] lg:py-6;
    @apply child-hover:bg-gray-400 child-hover:dark:bg-white child-hover:dark:bg-opacity-20 child:lg:gap-x-2;
    @apply child:flex child:w-full child:flex-col child:gap-y-2 child:rounded-lg child:p-2 child:lg:flex-row;
  }
  .fixed-badge {
    @apply absolute bottom-8 left-8 z-20 h-6 min-w-6 max-w-10 rounded-full bg-red-500 text-center;
  }
</style>
