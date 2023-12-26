<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3'
  import {
    mdiAt,
    mdiBackupRestore,
    mdiCheck,
    mdiEyeOffOutline,
    mdiEyeOutline,
    mdiLockOutline,
  } from '@mdi/js'
  import route from 'ziggy-js'

  const props = defineProps({
    email: String,
    token: String,
  })

  const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
  })

  const submit = () =>
    form.post(route('password.update'), {
      onFinish: () => form.reset('password', 'password_confirmation'),
    })

  const typeChange = reactive({
    isPassword: true,
    isConfPassword: true,
  })
</script>

<template lang="pug">
p-head(:title="$t('auth.reset-password')")
AppLayout
  .mx-10.flex.flex-col.gap-y-2
    v-text-field(
      ::="form.email",
      :error-messages="form.errors?.email",
      :label="$t('auth.email')",
      :prepend-inner-icon="mdiAt",
      :rules="[rules.email, rules.required]",
      color="secondary",
      type="email",
      variant="solo"
    ).text-right
    v-text-field(
      ::="form.password",
      :append-inner-icon="typeChange.isPassword ? mdiEyeOutline : mdiEyeOffOutline",
      :error-messages="form.errors?.password",
      :label="$t('auth.password')",
      :prepend-inner-icon="mdiLockOutline",
      :rules="[rules.required]",
      :type="typeChange.isPassword ? 'password' : 'text'",
      @click:append-inner="typeChange.isPassword = !typeChange.isPassword",
      color="secondary",
      hide-details="auto",
      variant="solo"
    ).text-right
    v-text-field(
      ::="form.password_confirmation",
      :append-inner-icon="typeChange.isConfPassword ? mdiEyeOutline : mdiEyeOffOutline",
      :error-messages="form.errors?.password_confirmation",
      :label="$t('auth.password_conf')",
      :prepend-inner-icon="mdiBackupRestore",
      :rules="[rules.required]",
      :type="typeChange.isConfPassword ? 'password' : 'text'",
      @click:append-inner="typeChange.isConfPassword = !typeChange.isConfPassword",
      color="secondary",
      hide-details="auto",
      variant="solo"
    ).text-right
    v-btn(
      :loading="form.processing",
      :prepend-icon="mdiCheck",
      @click="submit",
      color="secondary",
      rounded="lg"
    ).mt-4 {{ $t('auth.reset-password') }}
</template>
