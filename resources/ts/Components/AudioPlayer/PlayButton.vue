<script setup lang="ts">
defineProps({
  isPlaying: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['toggle']);
</script>

<template>
  <svg
    class="w-16 h-16"
    viewBox="0 0 408 408"
    xmlns="http://www.w3.org/2000/svg"
  >
    <circle
      cx="204"
      cy="204"
      r="200"
      style="fill: transparent; stroke-width: 8px"
      class="stroke-gray-900 dark:stroke-gray-200 cursor-pointer"
      @click="emit('toggle')"
    />
    <path
      v-if="!isPlaying"
      d="M354,203.947l-239.939,119.97l-0,-239.939l239.939,119.969Z"
      class="fill-gray-900 dark:fill-gray-200 cursor-pointer"
      @click="emit('toggle')"
    />
    <rect
      v-if="isPlaying"
      x="114.061"
      y="83.978"
      width="59.96"
      height="239.939"
      class="fill-gray-900 dark:fill-gray-200 cursor-pointer"
      @click="emit('toggle')"
    />
    <rect
      v-if="isPlaying"
      x="233.98"
      y="83.978"
      width="59.96"
      height="239.939"
      class="fill-gray-900 dark:fill-gray-200 cursor-pointer"
      @click="emit('toggle')"
    />
  </svg>
</template>
