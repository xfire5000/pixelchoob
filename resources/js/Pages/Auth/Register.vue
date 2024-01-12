<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3'
  import {
    mdiAccount,
    mdiAt,
    mdiBackupRestore,
    mdiCheck,
    mdiEyeOffOutline,
    mdiEyeOutline,
    mdiLockOutline,
  } from '@mdi/js'
  import route from 'ziggy-js'

  const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    terms: true,
  })

  const submit = () => {
    form.post(route('register'), {
      onFinish: () => form.reset('password', 'password_confirmation'),
    })
  }

  const typeChange = reactive({
    isPassword: true,
    isConfPassword: true,
  })
</script>

<template lang="pug">
p-head(:title="$t('auth.register')")
AuthLayout
  .flex.flex-col
    v-text-field(
      ::="form.name",
      :error-messages="form.errors?.name",
      :label="$t('auth.name')",
      :prepend-inner-icon="mdiAccount",
      :rules="[rules.required]",
      class="lg:w-2/3",
      color="secondary",
      hide-details="auto",
      variant="solo"
    ).mx-10.mt-4.text-right
    v-text-field(
      ::="form.email",
      :error-messages="form.errors?.email",
      :label="$t('auth.email')",
      :prepend-inner-icon="mdiAt",
      :rules="[rules.email, rules.required]",
      color="secondary",
      hide-details="auto",
      type="email",
      variant="solo"
    ).mx-10.mt-4.text-right
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
    ).mx-10.mt-4.text-right
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
    ).mx-10.my-4.text-right
    v-radio-group(
      ::="form.role",
      class="[&_.v-selection-control-group]:gap-x-4",
      color="primary",
      hide-details="auto",
      inline
    ).mx-10.mb-2
      v-radio(:label="$t('user')", value="user")
      v-radio(:label="$t('provider')", value="provider")
    v-divider.mx-10
    p-link(
      :href="route('login')",
      as="button",
      type="button"
    ).mx-12.mb-10.mt-3.text-right.text-xs.text-sky-600 {{ $t('auth.have-an-account') }}
    v-btn(
      :icon="mdiCheck",
      @click="submit",
      color="primary"
    ).absolute.bottom-6.right-6
</template>
