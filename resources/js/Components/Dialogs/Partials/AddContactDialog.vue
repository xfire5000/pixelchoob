<script setup lang="ts">
  import DialogModal from '../../DialogModal.vue'
  import {
    mdiArrowLeft,
    mdiInformationVariantCircleOutline,
    mdiMagnify,
    mdiMapMarker,
    mdiPhone,
  } from '@mdi/js'
  import { StatusCodes } from 'http-status-codes'
  import route from 'ziggy-js'

  interface IProps {
    show: boolean
  }

  withDefaults(defineProps<IProps>(), { show: false })

  const emit = defineEmits(['close', 'selectedUsers'])

  const search = ref<string>('')

  const users = ref<TPageProps>(null)

  const page = ref<number>(1)
  const lockFetch = ref<boolean>(false)

  function fetchUsers(isNewSearch: boolean = false) {
    if (search.value.length) {
      if (isNewSearch) {
        users.value = null
        page.value = 1
        lockFetch.value = false
      }
      useFetchClient
        .get<TPageProps>(
          route('search.contacts.index', {
            _query: { page: page.value, search: search.value },
          }),
        )
        .then(({ data, statusCode }) => {
          if (!users.value) users.value = data.value
          else users.value.data.concat(data.value.data)
          if (statusCode.value === StatusCodes.OK && data.value.data.length)
            page.value++
          else lockFetch.value = true
        })
    }
  }

  const selectedItems = ref<IUserItem[]>([])

  const el = ref<HTMLDivElement>(null)

  useInfiniteScroll(el, () => {
    if (!lockFetch.value) fetchUsers(), { interval: 4000 }
  })

  const addressType = (description: string) => _.isNumber(parseInt(description))
</script>

<template lang="pug">
DialogModal(:show, @close="emit('close')", max-width="lg")
  template(#title)
    | {{ $t('search-and-add-contacts') }}
  template(#content)
    v-row
      v-col(cols="12")
        v-alert(
          :icon="mdiInformationVariantCircleOutline",
          :text="$t('select-contacts-hint')",
          closable,
          color="info",
          variant="tonal"
        )
        v-text-field(
          ::="search",
          :append-inner-icon="mdiArrowLeft",
          :hint="$t('search-contacts-hint')",
          :placeholder="$t('search-placeholder')",
          :prepend-inner-icon="mdiMagnify",
          @click:append-inner="fetchUsers(true)",
          @keypress.enter="fetchUsers(true)",
          density="comfortable"
        ).mt-2
        template(v-if="users?.data.length")
          v-divider.m-2
          div(class="lg:max-h-85", ref="el").max-h-64.overflow-y-scroll
            v-list(rounded="lg", v-model:selected="selectedItems").my-4
              v-list-item(
                :title="$t('no-data')",
                v-show="!users.data.length"
              ).my-4.text-center
              v-list-item(
                :prepend-avatar="item.profile_photo_url",
                :title="item.name",
                :value="item",
                lines="three",
                v-for="item in users?.data"
              )
                template(#append="{ isSelected }")
                  v-checkbox-btn(:model-value="isSelected")
                template(#subtitle)
                  v-chip-group(color="info")
                    v-chip(
                      :key="item.id",
                      :prepend-icon="addressType(info.description) ? mdiPhone : mdiMapMarker",
                      v-for="info in item.addressInfos",
                      v-text="info.description"
                    )
  template(#footer)
    v-btn(
      :disabled="!selectedItems.length",
      @click="emit('selectedUsers', selectedItems)",
      color="secondary",
      rounded="lg"
    ) {{ $t('confirm') }}
</template>
