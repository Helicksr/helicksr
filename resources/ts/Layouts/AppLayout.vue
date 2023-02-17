<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { AppBanner, AppNavbar } from '~~/Components';

defineProps({
  title: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div>
    <Head :title="title" />

    <AppBanner />

    <div class="min-h-screen">
      <AppNavbar />

      <!-- Page Heading -->
      <header v-if="$slots.header" class="bg-white dark:bg-gray-500 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
