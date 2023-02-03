<script setup lang="ts">
import { onMounted, ref } from 'vue';

defineProps({
  modelValue: {
    type: String,
    default: '',
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: string): void;
}>();

const onInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  emits(changeEmitName, target.value);
};

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
  if (input.value?.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
  <input
    ref="input"
    class="border-gray-300 dark:border-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm dark:bg-gray-300 dark:text-black"
    :value="modelValue"
    @input="onInput"
  />
</template>
