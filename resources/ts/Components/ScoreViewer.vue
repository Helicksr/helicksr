<script setup lang="ts">
import { OpenSheetMusicDisplay } from 'opensheetmusicdisplay';
import { computed, nextTick, onMounted, PropType, ref, watch } from 'vue';

const props = defineProps({
  transcription: {
    type: [String, null] as PropType<string | null>,
    required: true,
  },
});

const showViewer = ref(true);

const renderViewer = async (container: HTMLElement, score: string) => {
  const osmd = new OpenSheetMusicDisplay(container, {
    autoResize: false,
    drawCredits: false,
    drawPartNames: false,
    disableCursor: true,
    drawingParameters: 'compacttight',
  });

  // osmd.setLogLevel('info');

  try {
    await osmd.load(score);
    // TODO: make sure only the first part (instrument) is displayed
    osmd.render();
  } catch (err) {
    console.error(err);
  }
};

const clearViewer = async () => {
  showViewer.value = false;
  await nextTick(() => {
    showViewer.value = true;
  });
};

const viewerContainer = ref<HTMLElement | null>(null);

// using computed to allow attaching a watcher
const updatedTranscription = computed(() => props.transcription);
watch(updatedTranscription, async (newValue) => {
  console.log({ container: viewerContainer.value, newValue });
  if (viewerContainer.value === null || newValue === null) return;
  await clearViewer();
  await renderViewer(viewerContainer.value, newValue);
});

onMounted(async () => {
  if (viewerContainer.value === null || props.transcription === null) return;
  await renderViewer(viewerContainer.value, props.transcription);
});
</script>

<template>
  <div v-if="showViewer" ref="viewerContainer" />
</template>
