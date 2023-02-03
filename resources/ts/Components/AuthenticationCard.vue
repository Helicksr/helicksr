<script setup lang="ts">
import { Card } from '~~/Components';
</script>

<template>
  <div
    class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
  >
    <div>
      <slot name="logo" />
    </div>

    <Card class="mt-6 sm:max-w-md">
      <slot />
    </Card>
  </div>
</template>
