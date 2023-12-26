<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3'
  import route from 'ziggy-js'

  const props = defineProps<{
    status: string
  }>()

  const form = useForm({})

  const submit = () => {
    form.post(route('verification.send'))
  }

  const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
  )
</script>

<template lang="pug">
p-head(:title="$t('auth.verify-email')")
AuthLayout
  .mx-10.flex.flex-col.gap-y-2
    div(class="dark:text-gray-200").text-sm.text-gray-600 {{ $t('auth.verify-desc') }}
    v-alert(
      :text="$t('auth.verify-sent')",
      type="info",
      v-if="verificationLinkSent"
    )
    v-form(@submit.prevent="submit")
      .flex.items-center.justify-between
        v-btn(
          :loading="form.processing",
          color="secondary",
          rounded="lg",
          type="submit"
        ) {{ $t('auth.resend-verification') }}
        div
          p-link(
            :href="route('profile.show')",
            class="hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
          ).rounded-md.text-sm.text-gray-600.underline {{ $t('auth.edit-profile') }}
          p-link(
            :href="route('logout')",
            as="button",
            class="hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2",
            method="post"
          ).ms-2.rounded-md.text-sm.text-gray-600.underline {{ $t('auth.logout') }}
</template>
