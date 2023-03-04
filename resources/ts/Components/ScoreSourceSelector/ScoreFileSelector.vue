<script setup lang="ts">
import { PropType, ref } from 'vue';
import { SecondaryButton } from '~~/Components';

defineProps({
  modelValue: {
    type: [String, null] as PropType<string | null | undefined>,
    default: undefined,
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

const getFileContentsAsText = async (file: Blob | File) =>
  new Promise<string>((resolve, reject) => {
    const reader = new FileReader();

    reader.onload = (e: ProgressEvent<FileReader>) => {
      resolve(e.target?.result as string);
    };

    reader.onerror = (e: ProgressEvent<FileReader>) => {
      reject(new Error('error reading file'));
    };

    reader.readAsText(file);
  });

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: string | null | undefined): void;
}>();

const scoreInput = ref<HTMLInputElement | null>(null);

const updateValue = async () => {
  const file = scoreInput.value?.files ? scoreInput.value.files[0] : null;

  if (file === null) {
    emits(changeEmitName, undefined);
    return;
  }

  const contents = await getFileContentsAsText(file);

  emits(changeEmitName, contents);
};

const clearValue = () => {
  if (scoreInput.value) scoreInput.value.value = '';
  emits(changeEmitName, null);
};

const restoreValue = () => {
  if (scoreInput.value) scoreInput.value.value = '';
  emits(changeEmitName, undefined);
};
</script>

<template>
  <div>
    <input
      ref="scoreInput"
      type="file"
      class="hidden"
      accept=".musicxml,application/vnd.recordare.musicxml+xml,application/vnd.recordare.musicxml-portable+xml,application/vnd.recordare.musicxml,application/xml"
      @input="updateValue"
    />
    <SecondaryButton
      class="mt-2 mr-2"
      type="button"
      @click.prevent="scoreInput?.click()"
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
