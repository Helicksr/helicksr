<script setup lang="ts">
import { PropType, ref } from 'vue';
import { SecondaryButton } from '~~/Components';

defineProps({
  modelValue: {
    type: [File, null] as PropType<File | null>,
    default: null,
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: File | null): void;
}>();

const audioInput = ref<HTMLInputElement | null>(null);

const updateValue = () => {
  const file = audioInput.value?.files? audioInput.value.files[0] : null;

  emits(changeEmitName, file);
};

const clearValue = () => {
  if (audioInput.value)
    audioInput.value.value = '';
  emits(changeEmitName, null);
};
</script>

<template>
  <input
    ref="audioInput"
    type="file"
    class="hidden"
    accept="audio/aac,audio/mpeg,audio/mp4,audio/ogg,audio/wav,audio/x-ms-wma"
    @input="updateValue"
  />
  <SecondaryButton
    class="mt-2 mr-2"
    type="button"
    @click.prevent="audioInput?.click()"
  >
    Select
  </SecondaryButton>
  <SecondaryButton
    type="button"
    class="mt-2 mr-2"
    v-if="modelValue"
    @click.prevent="clearValue"
  >
    Remove
  </SecondaryButton>
</template>