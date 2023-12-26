<script setup lang="ts">
  import {
    mdiAccount,
    mdiArrowRight,
    mdiAt,
    mdiBriefcaseAccountOutline,
    mdiChatPlusOutline,
    mdiDownload,
    mdiEmail,
    mdiFormatListGroup,
    mdiHammerWrench,
    mdiPageNextOutline,
    mdiPhone,
    mdiPlay,
    mdiShapeOutline,
    mdiText,
  } from '@mdi/js'
  import aos from 'aos'
  import { useForm } from 'laravel-precognition-vue'
  import { toast } from 'vue3-toastify'
  import { useDisplay } from 'vuetify'
  import route from 'ziggy-js'

  const { mobile } = useDisplay()

  const { t } = useI18n()

  const features: { title: string; description: string; icon: string }[] = [
    {
      title: t('features.personal-lists'),
      description: t('features.personal-lists-desc'),
      icon: mdiFormatListGroup,
    },
    {
      title: t('features.send-private'),
      description: t('features.send-private-desc'),
      icon: mdiBriefcaseAccountOutline,
    },
    {
      title: t('features.send-invoice'),
      description: t('features.invoice-desc'),
      icon: mdiPageNextOutline,
    },
    {
      title: t('features.send-tickets'),
      description: t('features.tickets-desc'),
      icon: mdiChatPlusOutline,
    },
  ]

  onMounted(() => aos.init())

  const form = useForm('post', route('contact.send'), {
    subject: '',
    description: '',
    email: '',
    name: '',
  })

  const submit = () =>
    form.submit({
      onSuccess(res) {
        form.reset()
        toast(res.data.msg, { type: 'success' })
      },
    })
</script>

<template lang="pug">
p-head(:title="$t('pixel-choob') + '&nbsp;' + $t('home-page')")
AppLayout
  .container.relative
    #circle-anim
    #pip-anim
    .grid.grid-cols-2.gap-2.py-12.backdrop-blur-md
      div(
        class="lg:col-span-1",
        data-aos="fade-up",
        data-aos-delay="300",
        data-aos-duration="3000"
      ).relative.col-span-full.flex.h-full.flex-col.items-center
        div(class="lg:w-2/3").m-auto.w-full
          v-img(:src="`/assets/img/pics/pixel-workout.png`", class="[&_img]:rounded-xl")
        .float-el1
          .flex.w-full.flex-row.items-center.justify-start
            v-icon(size="35").-mt-1.opacity-80 {{ mdiShapeOutline }}
            .px-12
        .float-el2
          .flex.w-full.flex-row.items-center.justify-start
            v-icon(size="35").-mt-12.opacity-80 {{ mdiDownload }}
            .py-8
      div(
        class="lg:col-span-1",
        data-aos="fade-left",
        data-aos-delay="300",
        data-aos-duration="3000"
      ).col-span-full
        div(:class="['lg:px-16 lg:py-30', { 'py-10': mobile }]")
          h1(:class="[{ 'text-3xl': mobile }, 'dark:text-white']")
            | {{ $t('pixel-hero') }}&nbsp;
            span(class="bg-300%")
              | {{ $t('with', { name: $t('pixel-choob') }) }}
          h6(class="dark:text-gray-200").mt-6.opacity-50 {{ $t('pixel-hero-desc') }}
          p-link(
            :href="$page.props.auth['user'] ? route('dashboard') : route('login')",
            as="button",
            type="button"
          )
            v-btn(
              class="hover:shadow-primary-outline hover:shadow-cyan-300",
              rounded="lg",
              size="x-large"
            ).mt-2.bg-cyan-400
              v-icon(class="ltr:mr-2 rtl:ml-2", v-if="!$page.props.auth['user']") {{ mdiHammerWrench }}
              v-avatar(
                :image="$page.props.auth['user'].profile_photo_url",
                class="ltr:-ml-2 ltr:mr-2 rtl:-mr-2 rtl:ml-2",
                size="small",
                v-else
              )
              | {{ $page.props.auth['user'] ? $t('go-dashboard') : $t('start-to-work') }}
    #features.rounded-xl.bg-blue-600.bg-opacity-40.py-8
      div(class="child:rounded-xl").mx-4.grid.grid-cols-4.gap-4
        div(
          class="hover:scale-102 dark:bg-dark-100 dark:text-white lg:col-span-1",
          v-for="item in features"
        ).col-span-full.bg-white.p-2.shadow-sm.transition
          .my-4.flex.flex-row.items-center.justify-center.gap-x-2
            div(
              class="rtl:rounded-bl-0"
            ).rounded-lg.border-2.border-cyan-600.p-1.text-cyan-600
              v-icon(size="40") {{ item.icon }}
          .text-center.text-lg {{ item.title }}
          p(v-text="item.description").my-2.w-full.text-xs.text-gray-500
    #services.my-6.p-6
      .m-4.flex.flex-row.items-center.gap-x-2
        div(
          class="lg:w-1/3",
          data-aos="fade-left",
          data-aos-delay="300",
          data-aos-duration="3000"
        ).flex.w-full.flex-col.gap-y-2
          h5(class="dark:text-white") {{ $t('services-free') }}
          p(v-text="$t('services-desc')").text-gray-500
          p-link(
            :href="$page.props.auth['user'] ? route('dashboard') : route('login')",
            as="button",
            type="button"
          ).mt-6.w-full
            v-btn(:prepend-icon="mdiPlay", color="warning", rounded="lg") {{ $t('start-journey') }}
        div(
          class="[&_.v-img]:transition",
          data-aos="fade-right",
          data-aos-delay="300",
          data-aos-duration="3000"
        ).group.relative.flex.grow.flex-row-reverse.items-center.justify-center
          v-img(
            :src="`/assets/img/safe-image.png`",
            alt="safe-logo",
            class="group-hover:scale-102",
            max-width="250"
          ).z-10
          v-img(
            :src="`/assets/img/logo.png`",
            alt="logo",
            class="group-hover:rotate-6 group-hover:shadow-md [&_img]:p-1 [&_img]:brightness-200"
          ).absolute.bottom-5.z-20.ml-28.w-40.rounded-full.border.bg-opacity-40.shadow-gray-200.backdrop-blur-lg
    #about-us.my-6.p-6
      .items-top.m-4.flex.flex-row.gap-x-2
        div(class="dark:bg-dark-200 lg:w-1/3").rounded-xl.bg-cyan-500.p-6
          div(class="child:text-white").my-auto.flex.flex-col
            strong(v-text="$t('about-info')").mr-2.mt-2
            .group.mt-4.flex.flex-row.items-center.justify-end
              div(class="child-hover:underline").dir-ltr.ml-4.flex.flex-col.gap-y-2
                a(href="tell:+989140010783")
                  small +989140010783
                a(href="tell:+989140010796")
                  small +989140010796
              .contact-icon-circle
                v-icon(class="group-hover:text-blue-600") {{ mdiPhone }}
            .group.mt-6.flex.flex-row.items-center.justify-end
              a(
                class="hover:underline",
                href="mailto:Support@pixelchoob.ir"
              ).ml-4.text-base Support@pixelchoob.ir
              .contact-icon-circle
                v-icon(class="group-hover:text-blue-600") {{ mdiAt }}
        .grow
          h5(class="dark:text-white").mr-4.mt-4.text-gray-600
            v-icon.ml-4 {{ mdiEmail }}
            | {{ $t('contact-form.contact') }}
          .m-4.mt-5.grid.grid-cols-2.gap-4
            div(class="lg:col-span-1").col-span-full
              v-text-field(
                :error-messages="$page.props.errors?.name",
                :label="$t('contact-form.your-name')",
                :prepend-inner-icon="mdiAccount",
                color="primary",
                hide-details="auto"
              )
            div(class="lg:col-span-1").col-span-full
              v-text-field(
                :error-messages="$page.props.errors?.email",
                :label="$t('auth.email')",
                :prepend-inner-icon="mdiAt",
                color="primary",
                hide-details="auto",
                type="email"
              )
            .col-span-full
              v-text-field(
                :error-messages="$page.props.errors?.subject",
                :label="$t('contact-form.subject')",
                :prepend-inner-icon="mdiText",
                color="primary",
                hide-details="auto"
              )
            .col-span-full
              v-textarea(
                :error-messages="$page.props.errors?.description",
                :label="$t('description')",
                color="primary",
                hide-details="auto",
                rows="3",
                variant="outlined"
              )
            .col-span-full.text-right
              v-btn(
                :disabled="!form.email.length || !form.description.length || !form.subject.length",
                :icon="mdiArrowRight",
                :loading="form.processing",
                @click="submit",
                class="child:text-white",
                color="primary"
              )
</template>

<style scoped>
  #circle-anim {
    @apply absolute right-14 top-24 animate-spin rounded-full bg-gradient-to-tr from-primary to-blue-600 p-20;
  }
  #pip-anim {
    @apply absolute left-14 top-85 rotate-45 animate-pulse rounded-full bg-gradient-to-tr from-orange-500 to-yellow-400 px-20 py-4;
  }
  h1 span {
    @apply animate-gradient bg-gradient-to-r from-sky-600 to-primary bg-clip-text text-transparent;
  }
  .float-el1 {
    @apply absolute -right-3 top-3/4 rounded-full rounded-tl-0 bg-gradient-to-tr from-blue-300 to-pink-200 px-6 py-3 text-blue-600 shadow-md dark:bg-dark-200 xl:right-20 xl:top-2/3;
  }
  .float-el2 {
    @apply absolute -left-2 top-2 rounded-br-full bg-green-600 px-3 py-6 text-white shadow-md dark:bg-dark-200 xl:left-24 xl:top-20;
  }
  .contact-icon-circle {
    @apply ml-4 rounded-full p-2 transition duration-300 group-hover:bg-white dark:group-hover:bg-slate-800;
  }
</style>
