<script setup lang="ts">
import { SecondaryButton } from '~~/Components';

defineProps({
  modelValue: {
    type: Blob,
    default: null,
  },
});
</script>

<template>
  <div>
    <SecondaryButton
      class="mt-2 mr-2"
      type="button"
    >
      Record
    </SecondaryButton>
    <SecondaryButton
      class="mt-2 mr-2"
      type="button"
    >
      Stop
    </SecondaryButton>
  </div>
</template>
