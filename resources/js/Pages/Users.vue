<!-- eslint-disable vue/prop-name-casing -->
<script setup lang="ts">
  import { router, usePage } from '@inertiajs/vue3'
  import {
    mdiArrowRight,
    mdiCheck,
    mdiDotsHorizontal,
    mdiPencil,
    mdiPlusCircleOutline,
    mdiTrashCanOutline,
  } from '@mdi/js'
  import { StatusCodes } from 'http-status-codes'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  defineProps<{
    users: TPageProps
    roles: IRoleItem[]
    addressTypes: { title: string; value: string }[]
  }>()

  const searchedParam = useUrlSearchParams('history').s

  const search = ref<string>(searchedParam ? searchedParam.toString() : '')

  const doSearch = () =>
    router.get(route('users.index', { _query: { s: search.value } }))

  const userDialog = ref<boolean>(false)
  const userItem = ref<IUserItem>(undefined)

  function userDialogActivator(item = undefined) {
    userItem.value = item
    userDialog.value = !_.isUndefined(item)
  }

  const { t } = useI18n()

  const headers: { title: string; key: string; sortable?: boolean }[] = [
    { title: t('name'), key: 'name' },
    { title: t('auth.email'), key: 'email' },
    { title: t('roles'), key: 'roles' },
    { title: t('created_at'), key: 'created_at', sortable: true },
    { title: t('actions'), key: 'actions' },
  ]

  const deleteDialog = ref<boolean>(false)

  function deleteUser(item = undefined) {
    userItem.value = item
    deleteDialog.value = !_.isUndefined(item)
  }

  const doDelete = () =>
    useFetchClient
      .delete<{ msg: string }>(route('users.destroy', userItem.value.id))
      .then(({ data, statusCode }) => {
        deleteDialog.value = false
        if (statusCode.value === StatusCodes.OK) {
          toast(data.value.msg, { type: 'success' })
          _.remove(
            usePage().props.users['data'],
            (item: IUserItem) => item.id == userItem.value.id,
          )
          userItem.value = undefined
        }
      })

  function onSubmitted(item: IUserItem) {
    let items = usePage().props.users['data']
    if (item.created_at === item.updated_at) items.unshift(item)
    else {
      let index = items.findIndex((u: IUserItem) => u.id === item.id)
      items[index] = item
    }
  }

  const selectedItems = ref<number[]>([])
</script>

<template lang="pug">
p-head(:title="$t('users')")
PanelLayout
  .container
    v-row
      v-col(cols="12", md="8")
        | {{ $t('users') }}
        SearchField(::="search", @do-search="doSearch", class="w-1/3").mt-4
      v-col(cols="12", md="4").flex.flex-row-reverse.items-center.gap-x-2
        p-link(:href="route('users.index')", v-if="search.length > 0")
          v-btn(
            :prepend-icon="mdiArrowRight",
            rounded="lg",
            variant="outlined"
          ).my-auto.ml-3 {{ $t('back') }}
        v-btn(
          :prepend-icon="mdiPlusCircleOutline",
          @click="userDialogActivator",
          color="secondary",
          rounded="lg",
          v-if="$page.props.auth['can']['add-users']"
        ).my-auto {{ $t('add', { name: $t('user') }) }}
      v-col(cols="12")
        v-chip-group
          p-link(:href="route('users.index')", as="button")
            v-chip.bg-sky-600.bg-opacity-30.text-sky-600 {{ $t('all') }}
          p-link(
            :href="route('users.index', { _query: { role: item.name } })",
            as="button",
            v-for="item in roles"
          )
            v-chip(:key="item.id").bg-sky-600.bg-opacity-30.text-sky-600
              v-icon(
                size="small",
                v-if="$page.props.ziggy['url'] === route('users.index')"
              ) {{ mdiCheck }}
              | {{ item.title }}
        v-data-table(
          ::="selectedItems",
          :headers,
          :items="users.data",
          :items-per-page="users.per_page",
          item-value="id",
          show-select
        )
          template(#bottom)
            Paginator(
              :current-page="users.current_page",
              :links="users.links",
              :total="users.last_page",
              type="api"
            ).w-full.p-4
          template(#item.roles="{ value }")
            v-chip-group.flex.flex-row.items-center.justify-start.gap-x-2
              v-chip(
                :key="item.id",
                v-for="item in value"
              ).bg-sky-600.bg-opacity-30.text-sky-600 {{ item.title }}
          template(#item.created_at="{ value }")
            span(dir="ltr") {{ $d(value, 'long') }}
          template(#item.name="{ value }")
            a(
              @click="userDialogActivator(value)",
              class="hover:underline",
              v-if="$page.props.auth['can']['edit-users']"
            ).cursor-pointer.text-sky-600 {{ value }}
            span(v-else) {{ value }}
          template(#item.actions="{ item }")
            v-menu(location="bottom")
              template(#activator="{ props: menu }")
                v-btn(
                  :icon="mdiDotsHorizontal",
                  size="small",
                  v-bind="menu",
                  variant="text"
                )
              v-list
                v-list-item(
                  :prepend-icon="mdiPencil",
                  @click="userDialogActivator(item)",
                  v-if="$page.props.auth['can']['edit-users']"
                ) {{ $t('edit') }}
                v-list-item(
                  :prepend-icon="mdiTrashCanOutline",
                  @click="deleteUser(item)",
                  v-if="$page.props.auth['can']['delete-users']"
                ) {{ $t('delete') }}
UserDialog(
  :address-types="addressTypes",
  :initial-form="userItem",
  :roles,
  :show="userDialog",
  @close="userDialogActivator()",
  @submitted="onSubmitted"
)
ConfirmationDialog(
  :show="deleteDialog",
  @close="deleteUser()",
  @yes="doDelete"
)
  template(#title) {{ $t('delete', { name: $t('user') }) }}
  template(#content) {{ $t('delete-confirmation', { name: $t('user') }) }}
</template>
