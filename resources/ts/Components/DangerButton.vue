<script setup lang="ts">
import { PropType } from 'vue';

defineProps({
  type: {
    type: String as PropType<'reset' | 'submit' | 'button'>,
    default: 'button',
  },
});
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 dark:bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition"
  >
    <slot />
  </button>
</template>
