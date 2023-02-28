<script setup lang="ts">
import { PropType, ref } from 'vue';
import { SecondaryButton } from '~~/Components';

defineProps({
  modelValue: {
    type: [Blob, null] as PropType<Blob | null | undefined>,
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

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: Blob | null | undefined): void;
}>();

const updateValue = (newValue: Blob | null | undefined) => {
  emits(changeEmitName, newValue);
};

const isInitialized = ref(false);
const isRecording = ref(false);
const recorder = ref<MediaRecorder | null>(null);

const onClickRecord = async () => {
  await initializeRecorder();

  if (recorder.value === null) return;

  isRecording.value = true;
  recorder.value.start();
};

const onClickStop = () => {
  if (recorder.value === null) return;

  isRecording.value = false;
  recorder.value.stop();
};

let chunks: BlobPart[] = [];

const initializeRecorder = async () => {
  try {
    chunks = [];
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    recorder.value = new MediaRecorder(stream, {
      audioBitsPerSecond: 128000,
    });
    recorder.value.ondataavailable = (e) => chunks.push(e.data);
    recorder.value.onstop = (e) => {
      updateValue(
        new Blob(chunks, {
          type: 'audio/webm; codecs=opus',
        })
      );
      chunks = [];
    };
    isInitialized.value = true;
  } catch (err: any) {
    console.error(err);
  }
};
</script>

<template>
  <div>
    <SecondaryButton
      v-if="!isRecording"
      class="mt-2 mr-2"
      type="button"
      @click="onClickRecord"
    >
      Record
    </SecondaryButton>
    <SecondaryButton
      v-if="isRecording"
      class="mt-2 mr-2"
      type="button"
      @click="onClickStop"
    >
      Stop
    </SecondaryButton>
    <SecondaryButton
      v-if="enableRemove"
      class="mt-2 mr-2"
      type="button"
      @click="updateValue(null)"
    >
      Remove
    </SecondaryButton>
    <SecondaryButton
      v-if="enableRestore"
      class="mt-2 mr-2"
      type="button"
      @click="updateValue(undefined)"
    >
      Restore
    </SecondaryButton>
  </div>
</template>
