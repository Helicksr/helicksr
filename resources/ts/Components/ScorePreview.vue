<script setup lang="ts">
import { computed, PropType } from 'vue';
import { ScoreViewer } from '~~/Components';
import { isValidXml } from '~~/utils';

const props = defineProps({
  modelValue: {
    type: String as PropType<string | undefined>,
    default: undefined,
  },
});

const isXml = computed(() => isValidXml(props.modelValue ?? ''));
</script>

<template>
  <ScoreViewer v-if="modelValue && isXml" :transcription="modelValue" />
  <code v-else>{{ modelValue }}</code>
</template>
