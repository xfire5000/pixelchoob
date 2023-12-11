<script setup lang="ts">
  import DialogModal from '../DialogModal.vue'
  import { mdiArrowLeftRight, mdiCheck, mdiPencil } from '@mdi/js'
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
      description: '<p class="ql-align-right"></p>',
      pvc: {
        reduce_thickness: false,
        size: '1',
        color_code: '',
      },
      stock: {
        sizes: { w: 183, h: 366 },
        qty: 1,
        color: '',
        pattern: false,
        material: '',
      },
      archived: 0,
    }),
  })

  const emit = defineEmits(['close', 'submitted'])

  const form = useForm('post', route('list-cases.store'), {
    ...props.initialForm,
  } as IListCaseItem)

  watch(
    () => props.initialForm,
    (newVal: any) => {
      if (props.show && props.initialForm.id) {
        let dataItem = useInitListCase(newVal)
        form.pvc = dataItem.pvc
        form.stock = dataItem.stock
      }
    },
  )

  const submit = () =>
    form.submit({
      method: props.initialForm.id ? 'patch' : 'post',
      url: props.initialForm.id
        ? route('list-cases.update', props.initialForm.id)
        : route('list-cases.store'),
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
    v-row(class="lg:max-h-85").max-h-64.overflow-y-scroll
      v-col(cols="12", md="6")
        v-text-field(
          ::="form.title",
          :error-messages="form.errors?.title",
          :label="$t('title')",
          class="[&_input]:text-xs",
          hide-details="auto"
        )
      v-divider.mx-2
      v-col(cols="12")
        h6(class="dark:text-white").text-gray-600 {{ $t('stock-settings') }}
        .flex.flex-row.gap-x-3
          div(
            class="hover:border-gray-800 dark:text-white dark:hover:border-white lg:w-1/2"
          ).flex.flex-row.items-center.justify-start.rounded-lg.border.border-gray-600.p-1
            .flex.flex-row.items-center.justify-start.gap-x-1
              v-icon {{ mdiArrowLeftRight }}
              input(
                ::="form.stock.sizes.w",
                :placeholder="$t('sizes.width')",
                class="w-1/3 focus:outline-none",
                step="1",
                type="number"
              )
              span.opacity-70 {{ $t('units.cm') }}
            v-divider(vertical).mx-1
            .flex.flex-row.items-center.justify-start.gap-x-1
              v-icon.rotate-90 {{ mdiArrowLeftRight }}
              input(
                ::="form.stock.sizes.h",
                :placeholder="$t('sizes.height')",
                class="w-1/3 focus:outline-none",
                step="1",
                type="number"
              )
              span.opacity-70 {{ $t('units.cm') }}
          div(
            class="hover:border-gray-800 dark:text-white dark:hover:border-white lg:w-1/6"
          ).flex.flex-row.items-center.justify-start.rounded-lg.border.border-gray-600.p-1
            .flex.flex-row.items-center.justify-start.gap-x-2
              label(for="qty") {{ $t('qty') }}
              input#qty(
                ::="form.stock.qty",
                :placeholder="$t('qty')",
                class="w-1/3 focus:outline-none",
                step="1",
                type="number"
              ).appearance-none
              span.opacity-70 {{ $t('units.count') }}
          .flex.grow.flex-row.items-center
            strong {{ $t('stock-pattern') }}:
            v-radio-group(
              ::="form.stock.pattern",
              color="secondary",
              hide-details="auto",
              inline
            ).mr-6
              v-radio(:value="false") {{ $t('having.not-have-it') }}
              v-radio(:value="true").mr-12 {{ $t('having.have-it') }}
        div(
          class="hover:border-gray-800 dark:text-white dark:hover:border-white lg:w-1/2"
        ).mt-2.flex.flex-row.items-center.justify-start.rounded-lg.border.border-gray-600.p-1
          .flex.flex-row.items-center.justify-start.gap-x-1
            label(for="material") {{ $t('material') }}
            input#material(::="form.stock.material", class="w-[80%] focus:outline-none")
          v-divider(vertical).mx-1
          .flex.flex-row.items-center.justify-start.gap-x-1
            label(for="color") {{ $t('color') }}
            input#color(::="form.stock.color", class="w-[80%] focus:outline-none")
      v-divider.mx-2
      v-col(cols="12")
        h6(class="dark:text-white").text-gray-600 {{ $t('pvc-settings') }}
        v-text-field(
          ::="form.pvc.color_code",
          :label="$t('color-code')",
          class="w-1/2 [&_input]:text-xs",
          hide-details="auto"
        )
        .item-center.flex.flex-row.gap-x-2
          v-checkbox(
            ::="form.pvc.reduce_thickness",
            :label="$t('reduce_thickness')",
            class="dark:text-white",
            color="primary",
            hide-details="auto"
          )
          .flex.grow.flex-row.items-center
            strong {{ $t('sizes.stroke') }}:
            v-radio-group(
              ::="form.pvc.size",
              color="secondary",
              hide-details="auto",
              inline
            ).mr-1
              v-radio(:label="$t('pvc-size.1mm')", value="1")
              v-radio(:label="$t('pvc-size.2mm')", value="2").mr-2
      v-divider.mx-2
      v-col(cols="12").editor-parent.pb-16
        h6(class="dark:text-white").text-gray-600 {{ $t('description') }}
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
      :prepend-icon="form.id ? mdiPencil : mdiCheck",
      @click="submit"
    ) {{ form.id ? $t('submit-edit') : $t('submit-store') }}
</template>
