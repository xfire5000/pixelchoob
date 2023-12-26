<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3'
  import { mdiAt, mdiCheck } from '@mdi/js'
  import route from 'ziggy-js'

  defineProps<{ status: string }>()

  const form = useForm({
    email: '',
  })

  const submit = () => form.post(route('password.email'))
</script>

<template lang="pug">
p-head(:title="$t('auth.forget-password')")
AuthLayout
  .mx-8
    div(class="dark:text-gray-200").break-words.text-sm.text-gray-600 {{ $t('auth.forgot-password-hint') }}
    v-alert(
      :text="status",
      closable,
      color="info",
      v-if="status"
    ).text-sm.font-medium
    .flex.flex-col
      v-text-field(
        ::="form.email",
        :error-messages="form.errors?.email",
        :label="$t('auth.email')",
        :prepend-inner-icon="mdiAt",
        :rules="[rules.email, rules.required]",
        color="secondary",
        type="email",
        variant="solo"
      ).mt-4.text-right
      v-btn(
        :loading="form.processing",
        :prepend-icon="mdiCheck",
        @click="submit",
        color="secondary",
        rounded="lg"
      ).mt-4 {{ $t('auth.send-reset-password-email') }}
</template>
