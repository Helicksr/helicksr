<script setup lang="ts">
import { computed, nextTick, PropType, ref, watch } from 'vue';
import { AudioPlayer } from '~~/Components';

const props = defineProps({
  modelValue: {
    type: [String, File, Blob, null] as PropType<
      string | File | Blob | null | undefined
    >,
    default: undefined,
  },
  originalUrl: {
    type: String as PropType<string | null>,
    default: null,
  },
});

const preview = ref<string | null>('');

const encodeBlob = async (blob: Blob | File) =>
  new Promise<string>((resolve, reject) => {
    const reader = new FileReader();

    reader.onload = (e: ProgressEvent<FileReader>) => {
      resolve(e.target?.result as string);
    };

    reader.onerror = (e: ProgressEvent<FileReader>) => {
      reject(new Error('error reading blob'));
    };

    reader.readAsDataURL(blob);
  });

const fetchData = async (url: string) => {
  const response = await fetch(url);

  if (response.ok) {
    return response.blob();
  }

  throw new Error('error fetching data');
};

// using computed to allow attaching a watcher
const updatedValue = computed(() => props.modelValue);
watch(
  updatedValue,
  async (newValue) => {
    preview.value = null; // first set null to force reload

    if (newValue === null) {
      return;
    }

    if (newValue === undefined) {
      await nextTick(() => {
        preview.value = props.originalUrl;
      });
      return;
    }

    if (typeof newValue === 'string') {
      // if url
      const audioData = await fetchData(newValue);
      preview.value = await encodeBlob(audioData);
      return;
    }

    preview.value = await encodeBlob(newValue);
  },
  { immediate: true }
);
</script>
<template>
  <AudioPlayer
    v-if="preview"
    :src="preview"
    :enable-repeat="false"
    :autoload="true"
  />
</template>
