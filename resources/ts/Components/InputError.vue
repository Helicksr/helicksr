<script setup lang="ts">
defineProps({
  message: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div v-show="message">
    <p class="text-sm text-red-600 dark:text-red-800">
      {{ message }}
    </p>
  </div>
</template>
