<script setup lang="ts">
  import DialogModal from '../DialogModal.vue'
  import { mdiCheck, type mdiPencil } from '@mdi/js'
  import { useForm } from 'laravel-precognition-vue'
  import { toast } from 'vue3-toastify'
  import route from 'ziggy-js'

  interface IProps {
    show: boolean
    initialForm?: IListCaseItem
  }

  const props = withDefaults(defineProps<IProps>(), {
    show: false,
    initialForm: () => ({
      title: '',
      description: '',
      pvc: '',
      stock: '',
      archived: 0,
      id: null,
    }),
  })

  const emit = defineEmits(['close', 'submitted'])

  let form = useForm('post', route('list-cases.store'), {
    ...props.initialForm,
  })

  watch(
    () => props.initialForm,
    (newVal: IListCaseItem) => {
      if (props.show && props.initialForm.id)
        form = useForm('patch', route('tasks.update', props.initialForm.id), {
          id: newVal.id,
          archived: newVal.archived,
          title: newVal.title,
          pvc: JSON.parse(newVal.pvc),
          stock: JSON.parse(newVal.stock),
          description: newVal.description,
          author_id: newVal.author_id,
          user_id: newVal.user_id,
        })
    },
  )

  onUpdated(() => {
    if (!props.show)
      form = useForm('post', route('list-cases.store'), {
        title: '',
        description: '',
        pvc: '',
        stock: '',
        archived: 0,
      })
  })

  const submit = () =>
    form.submit({
      onSuccess(res: any) {
        emit('submitted', res.data.item)
        toast(res.data.msg, { type: 'success' })
        form.reset()
        emit('close')
      },
    })
</script>

<template lang="pug">
DialogModal(:closeable="false", :show, @close="emit('close')")
  template(#title)
    | {{ form.id ? $t('edit', { name: form.title }) : $t('add', { name: $t('list') }) }}
  template(#content)
    v-row
      v-col(cols="12", md="6")
        v-text-field(
          ::="form.title",
          :error-messages="form.errors?.title",
          :label="$t('title')",
          hide-details="auto"
        )
      v-col(cols="12").editor-parent.pb-16
        v-alert(
          :text="form.description",
          closable,
          color="red",
          v-show="form.invalid('description')"
        )
        v-no-ssr
          p-editor(
            ::content="form.description",
            :placeholder="$t('description')",
            content-type="html"
          )
  template(#footer)
    v-btn(
      :color="form.id ? 'secondary' : 'primary'",
      :prepend-icon="form.id ? mdiPencil : mdiCheck"
    ) {{ form.id ? $t('submit-edit') : $t('submit-store') }}
</template>
