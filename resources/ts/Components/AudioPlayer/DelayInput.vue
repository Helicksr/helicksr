<script setup lang="ts">
import { TextInput } from '~~/Components';

const props = defineProps({
  modelValue: {
    type: Number,
    default: 0,
  },

  min: {
    type: Number,
    default: 0,
  },

  max: {
    type: Number,
    default: 5,
  },
});

const fitInLimits = (value: number) => {
  if (value > props.max) return props.max;

  if (value < props.min) return props.min;

  return value;
};

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: number): void;
}>();

const onInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const integer = parseInt(target.value);
  emits(
    changeEmitName,
    isNaN(integer) ? props.modelValue : fitInLimits(integer)
  );
};
</script>

<template>
  <TextInput
    :min="min"
    :max="max"
    type="number"
    class="w-16"
    :model-value="modelValue"
    @input="onInput"
  />
</template>
