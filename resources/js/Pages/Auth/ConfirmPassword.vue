<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3'
  import {
    mdiCheck,
    mdiEyeOffOutline,
    mdiEyeOutline,
    mdiLockOutline,
  } from '@mdi/js'
  import route from 'ziggy-js'

  const form = useForm({
    password: '',
  })

  const submit = () => {
    form.post(route('password.confirm'), {
      onFinish: () => {
        form.reset()
      },
    })
  }

  const isPassword = ref<boolean>(true)
</script>

<template lang="pug">
p-head(:title="$t('auth.secure-area')")
AuthLayout
  .mx-10.flex.flex-col.gap-y-2
    div(class="dark:text-gray-200").text-sm.text-gray-600 {{ $t('auth.secure-hint') }}
    v-form(@submit.prevent="submit")
      v-text-field(
        ::="form.password",
        :append-inner-icon="isPassword ? mdiEyeOffOutline : mdiEyeOutline",
        :label="$t('auth.password')",
        :prepend-inner-icon="mdiLockOutline",
        :rules="[rules.required]",
        :type="isPassword ? 'password' : 'text'",
        @click:append-inner="isPassword = !isPassword",
        @keypress.enter="submit",
        color="secondary",
        variant="solo"
      ).text-right
      v-btn(
        :loading="form.processing",
        :prepend-icon="mdiCheck",
        color="primary",
        rounded="lg",
        type="submit"
      ).mt-4 {{ $t('confirm') }}
</template>
