<script setup lang="ts">
import { PropType, ref } from 'vue';
import { SecondaryButton } from '~~/Components';

const props = defineProps({
  modelValue: {
    type: [File, null] as PropType<File | null | undefined>,
  },

  enableRemove: {
    type: Boolean,
    default: false,
  },

  enableRestore: {
    type: Boolean,
    default: false,
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: File | null | undefined): void;
}>();

const audioInput = ref<HTMLInputElement | null>(null);

const updateValue = () => {
  const file = audioInput.value?.files ? audioInput.value.files[0] : null;

  emits(changeEmitName, file);
};

const clearValue = () => {
  if (audioInput.value) audioInput.value.value = '';
  emits(changeEmitName, null);
};

const restoreValue = () => {
  if (audioInput.value) audioInput.value.value = '';
  emits(changeEmitName, undefined);
};
</script>

<template>
  <div>
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
      v-if="enableRemove"
      type="button"
      class="mt-2 mr-2"
      @click.prevent="clearValue"
    >
      Remove
    </SecondaryButton>
    <SecondaryButton
      v-if="enableRestore"
      type="button"
      class="mt-2 mr-2"
      @click.prevent="restoreValue"
    >
      Restore
    </SecondaryButton>
  </div>
</template>
