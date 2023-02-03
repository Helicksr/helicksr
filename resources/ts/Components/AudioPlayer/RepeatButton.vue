<script setup lang="ts">
defineProps({
  isRepeating: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['toggle']);
</script>

<template>
  <div>
    <svg
      viewBox="0 0 24 24"
      xmlns="http://www.w3.org/2000/svg"
      style="stroke-miterlimit: 1.5"
      @click="emit('toggle')"
    >
      <path
        class="cursor-pointer"
        :class="{
          'fill-gray-900 dark:fill-gray-200': isRepeating,
          'fill-gray-300 dark:fill-gray-400': !isRepeating,
        }"
        d="M13.8,13.66l-9,-6.75l9,-6.75l-0,13.5Z"
      />
      <path
        class="fill-transparent cursor-pointer"
        :class="{
          'stroke-gray-900 dark:stroke-gray-200': isRepeating,
          'stroke-gray-300 dark:stroke-gray-400': !isRepeating,
        }"
        d="M6.205,9.648c-1.074,1.309 -1.705,2.977 -1.705,4.762c-0,4.139 3.361,7.5 7.5,7.5c4.139,-0 7.5,-3.361 7.5,-7.5c0,-4.142 -3.358,-7.5 -7.5,-7.5"
        style="stroke-width: 3px"
      />
    </svg>
  </div>
</template>
