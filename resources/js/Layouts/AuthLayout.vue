<script setup lang="ts">
  import { loadSlim } from 'tsparticles-slim'
  import { useDisplay } from 'vuetify'
  import route from 'ziggy-js'

  const { mobile } = useDisplay()

  const particlesInit = async (engine) => {
    await loadSlim(engine)
  }

  const particlesOptions = loginParticlesOptions
</script>

<template lang="pug">
#back-pixel-choob
ThemeProvide.absolute.right-8.top-8.z-40
.container.mt-20
  v-row.my-auto
    v-col(class="lg:block", cols="12", md="8").relative.hidden
      v-img(:src="'/assets/img/safe-image.png'").z-20.my-auto.h-80
      vue-particles#tsparticles(
        :options="particlesOptions",
        :particlesInit="particlesInit"
      )
      #lg-mode
    v-col(cols="12", md="4").z-20
      #logo-section-auth
        p-link(:href="route('home.index')").mx-auto.my-6.w-30
          v-img(:src="`/assets/img/logo.png`", alt="logo", class="dark:brightness-200")
        slot
        #sm-mode
Teleport(:to="mobile ? '#sm-mode' : '#lg-mode'")
  v-img(:src="'/assets/img/p-agxic.png'", class="dark:brightness-100").brightness-0
</template>

<style scoped>
  #back-pixel-choob {
    @apply absolute top-0 z-0 h-full w-full bg-gradient-radial-to-bl from-sky-600 to-transparent;
  }
  #sm-mode,
  #lg-mode {
    @apply mx-auto mt-4 h-8 w-30;
  }
  #sm-mode {
    @apply lg:hidden;
  }
  #logo-section-auth {
    @apply hover:border-gray-600 hover:backdrop-blur-md dark:bg-white dark:bg-opacity-20 dark:hover:border-white dark:child:text-white;
    @apply relative flex flex-col justify-center rounded-3xl border border-transparent bg-gray-700 bg-opacity-20 pb-10 backdrop-blur-sm transition duration-300;
  }
</style>
