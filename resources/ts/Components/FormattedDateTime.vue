<script setup lang="ts">
import { DateTime } from 'luxon';

const props = defineProps({
  date: {
    type: String,
    required: true,
  },

  format: {
    type: String,
    default: 'yyyy-MM-dd',
  },
});

const parsed = DateTime.fromISO(props.date);

// Luxon automatically considers local browser timezone when generating formatted string
const formatted = parsed.toFormat(props.format);

const exact = parsed.toFormat('yyyy-MM-dd HH:mm:ss');
</script>

<template>
  <span :title="exact">{{ formatted }}</span>
</template>
