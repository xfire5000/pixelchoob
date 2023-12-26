<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3'
  import { mdiBackupRestore, mdiDotsHorizontal } from '@mdi/js'
  import route from 'ziggy-js'

  const recovery = ref(false)

  const form = useForm({
    code: '',
    recovery_code: '',
  })

  const recoveryCodeInput = ref(null)
  const codeInput = ref(null)

  const toggleRecovery = async () => {
    recovery.value !== true

    await nextTick()

    if (recovery.value) {
      recoveryCodeInput.value.focus()
      form.code = ''
    } else {
      codeInput.value.focus()
      form.recovery_code = ''
    }
  }

  const submit = () => {
    form.post(route('two-factor.login'))
  }
</script>

<template lang="pug">
p-head(:title="$t('auth.two-factor-confirm')")
AuthLayout
  .mx-10.flex.flex-col.gap-y-2
    div(class="dark:text-gray-200").text-sm.text-gray-600
      template(v-if="!recovery") {{ $t('auth.two-factor-do-access') }}
      template(v-else) {{ $t('auth.two-factor-do-emergency') }}
    v-form(@submit.prevent="submit")
      v-text-field(
        ::="form.code",
        :error-messages="form.errors?.code",
        :label="$t('auth.code')",
        :prepend-inner-icon="mdiDotsHorizontal",
        color="secondary",
        hide-details="auto",
        v-if="!recovery",
        variant="solo"
      ).text-right
      v-text-field(
        ::="form.recovery_code",
        :error-messages="form.errors?.recovery_code",
        :label="$t('auth.recover-code')",
        :prepend-inner-icon="mdiBackupRestore",
        color="secondary",
        hide-details="auto",
        v-else,
        variant="solo"
      ).text-right
      .flex.flex-row.items-center.gap-x-2
        v-btn(@click="toggleRecovery", rounded="lg", variant="outlined") {{ !recovery ? $t('auth.use-recovery') : $t('auth.use-auth-code') }}
        v-btn(
          :loading="form.processing",
          color="primary",
          rounded="lg",
          type="submit"
        ) {{ $t('auth.login') }}
</template>
