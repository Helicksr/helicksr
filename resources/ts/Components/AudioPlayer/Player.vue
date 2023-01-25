<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import WaveSurfer from 'wavesurfer.js';
import CursorPlugin from "wavesurfer.js/src/plugin/cursor";
import PlayButton from './PlayButton.vue';
import RepeatButton from './RepeatButton.vue';
import colors from 'tailwindcss/colors';

const props = defineProps({
  src: {
    type: String,
    required: true,
  },
});

const sleep = (seconds: number) =>
  new Promise(resolve =>
    setTimeout(resolve, seconds * 1000)
  );

const wavesurfer = ref<WaveSurfer | null>(null);
const wave = ref<HTMLElement | null>(null);

const state = ref({
  isPlaying: computed(() => wavesurfer.value?.isPlaying()),
  isLoaded: false,
  isRepeating: false,
  repeatDelay: 0,
});

const load = () => {
  if (wave.value === null) return;
  wavesurfer.value = WaveSurfer.create({
    container: wave.value,
    barWidth: 2,
    barHeight: 1,
    plugins: [
      CursorPlugin.create({
        showTime: true,
        opacity: '1.0',
        customShowTimeStyle: {
          'background-color': colors.black,
          color: colors.white,
          padding: '0.5em',
          'font-size': '10px'
        }
      }),
    ]
  });

  wavesurfer.value.on('error', function(e) {
      console.error(e);
  });

  wavesurfer.value.on('pause', async () => {
    const current = wavesurfer.value?.getCurrentTime();
    const duration = wavesurfer.value?.getDuration();

    if (current === duration && state.value.isRepeating) {
      // TODO: sleep for delay duration
      await sleep(state.value.repeatDelay);

      wavesurfer.value?.seekTo(0);
      wavesurfer.value?.play();
    }
  });

  wavesurfer.value.load(props.src);

  state.value.isLoaded = true;
};

const togglePlayPause = () => {
  return wavesurfer.value?.playPause();
};

const toggleRepeat = () => {
  state.value.isRepeating = !state.value.isRepeating;
};
</script>

<template>
  <div class="flex w-full">
    <div class="flex-none mr-2 flex flex-col">
      <div class="flex grow justify-center">
        <PlayButton
          class="mb-4"
          :is-playing="wavesurfer?.isPlaying()"
          @toggle="togglePlayPause"
        />
      </div>
      <div class="flex justify-center">
        <RepeatButton
          class="w-8 h-8"
          :is-repeating="state.isRepeating"
          @toggle="toggleRepeat"
        />
      </div>
    </div>
    <div class="flex-1">
      <div ref="wave" />
      <div v-show="!state.isLoaded" class="w-full h-full flex align-middle justify-center">
        <button @click="load" class="p-2 bg-blue-400 inline-block">
          Click here to load player
        </button>
      </div>
    </div>
  </div>
</template>
