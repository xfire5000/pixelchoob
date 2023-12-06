<script setup lang="ts">
  import ConfirmationModal from '../ConfirmationModal.vue'
  import DialogModal from '../DialogModal.vue'
  import SearchField from '../SearchField.vue'
  import AddContactDialog from './Partials/AddContactDialog.vue'
  import {
    mdiAccountGroup,
    mdiAccountPlus,
    mdiAlertOutline,
    mdiTrashCanOutline,
  } from '@mdi/js'
  import { StatusCodes } from 'http-status-codes'
  import route from 'ziggy-js'

  interface IProps {
    show: boolean
  }

  withDefaults(defineProps<IProps>(), {
    show: false,
  })

  const emit = defineEmits(['close', 'selectedUser'])

  const contacts = ref<TPageProps>(null)

  const page = ref<number>(1)
  const lockFetch = ref<boolean>(false)

  async function fetchContacts(isNewFetch: boolean = false) {
    if (isNewFetch) {
      contacts.value = null
      page.value = 1
      lockFetch.value = false
    }
    await useFetchClient
      .get<TPageProps>(
        route('contacts.index', {
          _query: _.merge(
            { page: page.value },
            search.value.length ? { s: search.value } : null,
          ),
        }),
      )
      .then(({ data, statusCode }) => {
        if (!contacts.value) contacts.value = data.value
        else contacts.value.data.concat(data.value.data)
        if (statusCode.value === StatusCodes.OK && data.value.data.length)
          page.value++
        else lockFetch.value = true
      })
  }

  const search = ref<string>('')

  const addContactDialog = ref<boolean>(false)

  const onSelectedUser = (args: IUserItem[]) =>
    useFetchClient
      .post(route('contacts.store'), {
        user_ids: args.map((item) => item.id),
      })
      .then(({ statusCode }) => {
        if (statusCode.value === StatusCodes.NO_CONTENT)
          contacts.value.data = [...contacts.value.data, ...args]
        addContactDialog.value = false
      })

  const el = ref<HTMLDivElement>(null)

  useInfiniteScroll(
    el,
    () => {
      if (!lockFetch.value) fetchContacts()
    },
    { interval: 4000 },
  )

  const contactId = ref<number>(0)
  const deleteDialog = ref<boolean>(false)

  function initOrDestroyDelete(id: number = 0) {
    deleteDialog.value = id !== 0
    contactId.value = id
  }

  const doDelete = () =>
    useDelete('contacts.destroy', contactId.value).then(() => {
      deleteDialog.value = false
      _.remove(contacts.value.data, (item) => item.id === contactId.value)
      contactId.value = 0
    })
</script>

<template lang="pug">
DialogModal(:show, @close="emit('close')", max-width="lg")
  template(#title)
    v-icon.ml-2 {{ mdiAccountGroup }}
    | {{ $t('my-contacts') }}
  template(#content)
    v-row
      v-col(cols="6")
        v-btn(
          :prepend-icon="mdiAccountPlus",
          @click="addContactDialog = true",
          color="secondary",
          rounded="lg"
        ) {{ $t('add', { name: $t('contacts') }) }}
      v-col(cols="6")
        SearchField(::="search", @do-search="fetchContacts(true)")
      v-col(cols="12")
        v-alert(
          :icon="mdiAlertOutline",
          :text="$t('select-to-send')",
          closable,
          color="warning",
          variant="tonal"
        )
      v-col(cols="12")
        div(class="lg:max-h-80", ref="el").max-h-64.overflow-y-scroll
          v-list(rounded="lg")
            v-list-item(v-show="!contacts?.data.length").my-4.text-center {{ $t('no-data') }}
            v-list-item(
              :key="item.id",
              :prepend-avatar="item.profile_photo_url",
              :title="item.name",
              v-for="item in contacts?.data"
            )
              template(#append)
                v-btn(
                  :icon="mdiTrashCanOutline",
                  @click="initOrDestroyDelete(item.id)",
                  color="red",
                  size="small",
                  variant="tonal"
                ).ml-2
                v-btn(
                  @click="emit('selectedUser', item.id)",
                  color="primary",
                  rounded="lg",
                  variant="tonal"
                ) {{ $t('select') }}
AddContactDialog(
  :show="addContactDialog",
  @close="addContactDialog = false",
  @selected-users="onSelectedUser"
)
ConfirmationModal(
  :show="deleteDialog",
  @close="initOrDestroyDelete()",
  @yes="doDelete"
)
  template(#title)
    | {{ $t('delete', { name: $t('contact') }) }}
  template(#content)
    | {{ $t('delete-confirmation', { name: $t('contact') }) }}
</template>
