<script setup lang="ts">
import { PropType, ref } from 'vue';
import { SecondaryButton } from '~~/Components';

defineProps({
  modelValue: {
    type: [Blob, null] as PropType<Blob | null>,
    default: null,
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: Blob | null): void;
}>();

const updateValue = (newValue: Blob | null) => {
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
          'type' : 'audio/webm; codecs=opus',
        }),
      );
      chunks = [];
    }
    isInitialized.value = true;
  } catch (err: any) {
    console.error(err);
  }
};

</script>

<template>
  <div>
    <SecondaryButton
      class="mt-2 mr-2"
      type="button"
      v-if="!isRecording"
      @click="onClickRecord"
    >
      Record
    </SecondaryButton>
    <SecondaryButton
      class="mt-2 mr-2"
      type="button"
      v-if="isRecording"
      @click="onClickStop"
    >
      Stop
    </SecondaryButton>
  </div>
</template>
