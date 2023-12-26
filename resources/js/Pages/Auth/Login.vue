<script setup lang="ts">
  import { useForm, usePage } from '@inertiajs/vue3'
  import {
    mdiAccountPlus,
    mdiArrowRight,
    mdiAt,
    mdiCheck,
    mdiEyeOffOutline,
    mdiEyeOutline,
    mdiInformationVariantCircleOutline,
    mdiLockOutline,
    mdiLockReset,
  } from '@mdi/js'
  import { TransitionSlide } from '@morev/vue-transitions'
  import { useReCaptcha } from 'vue-recaptcha-v3'
  import route from 'ziggy-js'

  defineProps<{
    canResetPassword: boolean
    status: string
  }>()

  const form = useForm({
    email: '',
    password: '',
    remember: false,
    captcha_token: null,
  })

  const { t } = useI18n()

  const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()

  const recaptcha = async () => {
    if (
      import.meta.env.VITE_GOOGLE_RECAPTCHA_SITE_KEY !== '' &&
      import.meta.env.VITE_RECAPTCHA_ENABLE
    ) {
      await recaptchaLoaded()
      form.captcha_token = await executeRecaptcha('login')
    }
    form
      .transform((data) => ({
        ...data,
        remember: form.remember ? 'on' : '',
      }))
      .post(route('login'), {
        onFinish: () => form.reset('password'),
        onError() {
          if (usePage().props.errors['email']) step.value -= 1
        },
      })
  }

  const submit = () => {
    if (2 > step.value) step.value++
    else recaptcha()
  }

  const step = ref<number>(1)
  const isPassword = ref<boolean>(true)
</script>

<template lang="pug">
p-head(:title="$t('auth.login')")
AuthLayout
  small(class="dark:text-white").mr-6 {{ $t('auth.welcome-login') }}
  p(class="dark:text-gray-200").mx-6.my-4.text-right.text-xs.text-gray-600
    v-icon(size="small").ml-2 {{ mdiInformationVariantCircleOutline }}
    | {{ $t('auth.login-hint') }}
  TransitionSlide
    div(v-if="step === 1").flex.flex-col
      v-text-field(
        ::="form.email",
        :focused="step === 1",
        :label="$t('auth.email')",
        :prepend-inner-icon="mdiAt",
        :rules="[rules.email, rules.required]",
        @keypress.enter="recaptcha",
        color="secondary",
        type="email",
        variant="solo"
      ).mx-10.mt-4.text-right
      v-switch(
        ::="form.remember",
        :label="$t('auth.remember-me')",
        color="secondary",
        hide-details="auto",
        inset
      ).mx-10.mb-2
    v-text-field(
      ::="form.password",
      :append-inner-icon="isPassword ? mdiEyeOffOutline : mdiEyeOutline",
      :focused="step === 2",
      :label="$t('auth.password')",
      :prepend-inner-icon="mdiLockOutline",
      :rules="[rules.required]",
      :type="isPassword ? 'password' : 'text'",
      @click:append-inner="isPassword = !isPassword",
      @keypress.enter="submit",
      color="secondary",
      v-else,
      variant="solo"
    ).mx-10.mt-4.text-right
  v-divider.mx-10
  div(
    class="[&_a]:flex [&_a]:flex-row [&_a]:items-center [&_a]:gap-x-2"
  ).mx-12.mb-10.mt-3.flex.flex-col.gap-y-2.text-right.text-xs.text-sky-600
    p-link(:href="route('password.request')")
      v-icon {{ mdiLockReset }}
      | {{ $t('auth.forgot_password') }}
    p-link(:href="route('register')")
      v-icon {{ mdiAccountPlus }}
      | {{ $t('auth.no-account-go-register') }}
  v-btn(
    :icon="2 > step ? mdiArrowRight : mdiCheck",
    :loading="form.processing",
    @click="submit",
    color="secondary"
  ).absolute.bottom-6.right-6
  v-btn(
    :icon="mdiArrowRight",
    @click="step--",
    size="small",
    v-if="step > 1",
    variant="text"
  ).absolute.left-4.top-4.rotate-180
</template>
