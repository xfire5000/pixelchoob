<script setup lang="ts">
  import DialogModal from '../DialogModal.vue'
  import {
    mdiFolderAlert,
    mdiPencil,
    mdiPlus,
    mdiTrashCanOutline,
  } from '@mdi/js'
  import { useForm } from 'laravel-precognition-vue'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  const props = withDefaults(
    defineProps<{
      show: boolean
      type: string
      items: IAddressInfo[]
      types: { title: string; value: string }[]
    }>(),
    { show: false },
  )

  const emit = defineEmits(['close', 'submitted', 'updated', 'deleted'])

  const form = useForm('post', route('address.store'), {
    description: '',
    isShow: 0,
    type: props.type,
  } as IAddressInfo)

  const submit = () =>
    form.submit({
      method: form.id ? 'patch' : 'post',
      url: form.id ? route('address.update', form.id) : route('address.store'),
      onSuccess(res) {
        toast(res.data.msg, { type: 'success' })
        emit(form.id ? 'updated' : 'submitted', res.data.item)
        form.reset()
        form.id = undefined
      },
    })

  const deleteAddress = (id: number) =>
    useFetchClient
      .delete<{ msg: string }>(route('address.destroy', id))
      .then(({ data }) => {
        toast(data.value.msg, { type: 'success' })
        emit('deleted', id)
      })
</script>

<template lang="pug">
DialogModal(:closeable="false", :show, @close="emit('close')")
  template(#title)
    | {{ $t('list', { name: types[type].title }) }}
  template(#content)
    v-list
      v-list-item
        .my-2.flex.flex-row.items-center.justify-between
          v-textarea(
            ::="form.description",
            :error-messages="form.errors?.description",
            :label="$t('description')",
            class="[&_input]:text-xs",
            hide-details="auto",
            max-rows="1",
            rows="1",
            variant="outlined"
          )
          v-checkbox(
            ::="form.isShow",
            :error-messages="form.errors?.isShow",
            :false-value="0",
            :label="$t('address.choose-as-default')",
            :true-value="1",
            class="lg:w-1/6 [&_.v-label]:text-xs",
            hide-details="auto",
            v-if="form.type === 'address'"
          ).text-xs
          v-btn(
            :color="form.id ? 'secondary' : 'primary'",
            :disabled="!form.description.length",
            :loading="form.processing",
            :prepend-icon="form.id ? mdiPencil : mdiPlus",
            @click="submit",
            rounded="lg",
            size="x-small",
            variant="text"
          ).my-auto {{ form.id ? $t('edit', { name: types[type].title }) : $t('add', { name: types[type].title }) }}
      v-list-item(v-if="!items.length")
        .flex.flex-row.items-center.justify-center.gap-x-2.py-8
          v-icon {{ mdiFolderAlert }}
          | {{ $t('no-data') }}
      v-list-item(
        :key="item.id",
        v-for="item in items",
        v-show="form.id !== item.id"
      )
        .flex.flex-row.items-center.justify-between
          v-textarea(
            ::="item.description",
            class="[&_input]:text-xs",
            hide-details="auto",
            max-rows="1",
            readonly,
            rows="1",
            variant="outlined"
          )
          v-checkbox(
            ::="item.isShow",
            :false-value="0",
            :label="$t('address.choose-as-default')",
            :true-value="1",
            class="lg:w-1/6 [&_.v-label]:text-xs",
            hide-details="auto",
            readonly,
            v-if="item.type === 'address'"
          ).text-xs
          v-btn(
            :prepend-icon="mdiPencil",
            @click="form.setData(item)",
            color="secondary",
            rounded="lg",
            size="x-small",
            variant="text"
          ) {{ $t('edit', { name: types[type].title }) }}
          v-btn(
            :prepend-icon="mdiTrashCanOutline",
            @click="deleteAddress(item.id)",
            color="red",
            rounded="lg",
            size="x-small",
            variant="text"
          ) {{ $t('delete') }}
</template>
