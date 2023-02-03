<script setup lang="ts">
import {
  OpenSheetMusicDisplay,
  Cursor,
  VoiceEntry,
  Note,
  StemDirectionType,
} from 'opensheetmusicdisplay';
import { onMounted, ref } from 'vue';

const props = defineProps({
  transcription: {
    type: String,
    required: true,
  },
});

const viewerContainer = ref<HTMLElement | null>(null);

const renderViewer = async () => {
  if (viewerContainer.value === null) return;

  const osmd = new OpenSheetMusicDisplay(viewerContainer.value, {
    autoResize: false,
    drawCredits: false,
    drawPartNames: false,
    disableCursor: true,
    drawingParameters: 'compacttight',
  });

  osmd.setLogLevel('info');

  try {
    await osmd.load(props.transcription);
    // TODO: make sure only the first part (instrument) is displayed
    osmd.render();
  } catch (err) {
    console.error(err);
  }
};

onMounted(() => {
  renderViewer();
});
</script>

<template>
  <div ref="viewerContainer"></div>
</template>
