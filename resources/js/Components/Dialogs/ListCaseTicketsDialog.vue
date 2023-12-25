<script setup lang="ts">
  import DialogModal from '../DialogModal.vue'
  import SearchField from '../SearchField.vue'
  import {
    mdiChatAlertOutline,
    mdiChatOutline,
    mdiCheck,
    mdiCheckAll,
    mdiSend,
    mdiStickerEmoji,
  } from '@mdi/js'
  import { StatusCodes } from 'http-status-codes'
  import { useForm } from 'laravel-precognition-vue'
  import EmojiPicker from 'vue3-emoji-picker'
  import type { EmojiExt } from 'vue3-emoji-picker'
  import 'vue3-emoji-picker/css'
  import route from 'ziggy-js'

  interface IProps {
    show: boolean
    listCaseId: number
  }

  const props = withDefaults(defineProps<IProps>(), { show: false })

  const emit = defineEmits(['close'])

  const tickets = ref<ITicketMessageItem[]>([])

  const search = ref<string>('')

  const page = ref<number>(1)
  const lockFetch = ref<boolean>(false)

  function resetTicketsDialog() {
    tickets.value = []
    page.value = 1
    lockFetch.value = false
  }

  function fetchTickets(isNewFetch: boolean = false) {
    if (isNewFetch) resetTicketsDialog()
    useFetchClient
      .get<any>(
        route('tickets.index', {
          _query: _.merge(
            { lid: props.listCaseId, page: page.value },
            search.value.length ? { s: search.value } : null,
          ),
        }),
      )
      .then(({ data, statusCode }) => {
        if (statusCode.value === StatusCodes.OK && data.value.data.length) {
          page.value++
          tickets.value = [...(data.value.data as ITicketMessageItem[])]
          el.value.scrollTo()
        } else lockFetch.value = true
      })
  }

  const el = ref<HTMLDivElement>(null)

  useInfiniteScroll(
    el,
    () => {
      if (!lockFetch.value) fetchTickets()
    },
    { interval: 4000, direction: 'top' },
  )

  const form = useForm('post', route('tickets.store'), {
    message: '',
    list_case_id: props.listCaseId,
  } as ITicketMessageItem)

  const submit = () =>
    form.submit({
      onSuccess(res) {
        tickets.value.unshift(res.data)
        form.reset()
      },
    })

  const onSelectEmoji = (emoji: EmojiExt) => (form.message += emoji.i)

  onUpdated(() => {
    if (!props.show && tickets.value.length) resetTicketsDialog()
  })
</script>

<template lang="pug">
DialogModal(:closeable="false", :show, @close="emit('close')")
  template(#title)
    .flex.flex-row.items-center.justify-start.gap-x-2
      v-icon(size="small") {{ mdiChatOutline }}
      | {{ $t('tickets') }}
  template(#content)
    SearchField(
      ::="search",
      @do-search="fetchTickets(true)",
      v-if="tickets.length"
    )
    div(ref="el").my-4.flex.max-h-64.flex-col-reverse.overflow-y-scroll
      div(
        v-if="!tickets.length"
      ).my-6.flex.flex-col.items-center.justify-center.gap-y-2
        v-icon(size="35") {{ mdiChatAlertOutline }}
        | {{ $t('no-data') }}
      div(v-for="item in tickets").my-2.grid.grid-cols-3
        div(v-if="item.user_id === $page.props.auth['user'].id").owner-chat
          v-avatar(:image="item.user.profile_photo_url", size="small")
          .chat-text
            strong.text-xs {{ item.user.name }}
            .text-xs {{ item.message }}
            small.justify-end
              | {{ $d(item.created_at, 'long') }}
              v-icon {{ item.created_at === item.updated_at ? mdiCheck : mdiCheckAll }}
        div(v-else).other-chat.col-start-2
          .chat-text
            strong.text-xs {{ item.user.name }}
            .text-xs {{ item.message }}
            small
              v-icon {{ item.created_at === item.updated_at ? mdiCheck : mdiCheckAll }}
              | {{ $d(item.created_at, 'long') }}
          v-avatar(:image="item.user.profile_photo_url", size="small")
    v-textarea(
      ::="form.message",
      :placeholder="$t('description')",
      class="[&input]:text-xs",
      hide-details="auto",
      max-rows="3",
      rows="2"
    )
      template(#append-inner)
        v-menu(:close-on-content-click="false", location="bottom")
          template(#activator="{ props: menu }")
            v-btn(icon, size="x-small", v-bind="menu", variant="text")
              v-icon(class="ml-0.4", size="16") {{ mdiStickerEmoji }}
          EmojiPicker(
            :hide-group-names="false",
            @select="onSelectEmoji",
            class="[&_h5]:hidden",
            display-recent,
            native
          )
      template(#prepend-inner)
        v-btn(
          :disabled="!form.message.length",
          @click="submit",
          color="primary",
          icon,
          size="x-small",
          variant="outlined"
        )
          v-icon(class="ml-0.4", size="16") {{ mdiSend }}
</template>

<style scoped>
  .owner-chat,
  .other-chat {
    @apply col-span-2 flex flex-row gap-x-2;
  }
  .chat-text {
    @apply flex flex-col break-all rounded-xl p-2 text-sm;
  }
  .chat-text small {
    @apply flex flex-row items-center gap-x-1 text-left text-xxs;
  }
  .owner-chat > .chat-text {
    @apply bg-primary bg-opacity-20 text-primary ltr:rounded-tl-0 rtl:rounded-tr-0;
  }
  .other-chat {
    @apply justify-end;
  }
  .other-chat > .chat-text {
    @apply bg-secondary bg-opacity-20 text-secondary ltr:rounded-tr-0 rtl:rounded-tl-0;
  }
</style>
