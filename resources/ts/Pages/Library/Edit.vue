<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { onMounted, PropType, ref } from 'vue';
import route from 'ziggy-js';
import ActionMessage from '~~/Components/ActionMessage.vue';
import AmpSettings from '~~/Components/AmpSettings.vue';
import Player from '~~/Components/AudioPlayer/Player.vue';
import Card from '~~/Components/Card.vue';
import InputError from '~~/Components/InputError.vue';
import InputLabel from '~~/Components/InputLabel.vue';
import PageTitle from '~~/Components/PageTitle.vue';
import PrimaryButton from '~~/Components/PrimaryButton.vue';
import SecondaryButton from '~~/Components/SecondaryButton.vue';
import TabViewer from '~~/Components/TabViewer.vue';
import TagSelector from '~~/Components/TagSelector.vue';
import TextInput from '~~/Components/TextInput.vue';

const props = defineProps({
  lick: {
    type: Object as PropType<App.Models.Lick>,
    required: true,
  },
  author: {
    type: String,
  },
});

const form = useForm<{
  _method: string,
  title: string,
  transcription: string | null, // <- export musicxml from tab or score in other program for now
  audio: null,
  tempo: string, // <- detected from audio? let's put a button to autodetect next to the field
  tags: string[],
  amp_settings: App.Models.AmpSetting[],
}>({
  _method: 'PUT',
  title: props.lick.title,
  transcription: null, // <- export musicxml from tab or score in other program for now
  audio: null,
  tempo: props.lick.tempo.toString(),
  tags: props.lick.tags,
  amp_settings: props.lick.amp_settings,
});


const submit = () => {
  form.transform(data => ({
    ...data,
    transcription: transcriptionPreview.value,
    audio: audioInput.value.files[0],
  })).post(route('library.update', { lick: props.lick }), {
    errorBag: 'submit',
    preserveScroll: true,
  });
};

// score/tab field handling
const transcriptionPreview = ref<string | null>(null);
const transcriptionInput = ref<any>(null); // TODO: find correct typings for this variable

const updateTranscriptionPreview = () => {
  const file = transcriptionInput.value?.files[0];

  if (! file) return;

  transcriptionPreview.value = null; // reset value to force reload

  const reader = new FileReader();

  reader.onload = (e: ProgressEvent<FileReader>) => {
    transcriptionPreview.value = e.target?.result as string;
  };

  reader.readAsText(file);
};

// audio field handling
const audioPreview = ref<string | null>(null);
const audioInput = ref<any>(null); // TODO: find correct typings for this variable

const updateAudioPreview = () => {
  const file = audioInput.value?.files[0];

  if (! file) return;

  audioPreview.value = null; // reset value to force reload

  const reader = new FileReader();

  reader.onload = (e: ProgressEvent<FileReader>) => {
    audioPreview.value = e.target?.result as string; // encoded as Base64
  };

  reader.readAsDataURL(file);
};

onMounted(() => {
  transcriptionPreview.value = props.lick.transcription;
  audioPreview.value = props.lick.audio_file_url;
});
</script>

<template>
  <AppLayout :title="lick.title">
    <template #header>
      <PageTitle>{{ lick.title }}</PageTitle>
    </template>

    <div class="max-w-4xl mx-auto sm:my-4">
      <form @submit.prevent="submit">
        <Card>
          <div class="mb-4">
            <InputLabel for="title" value="Title" />
            <TextInput
              id="title"
              v-model="form.title"
              type="text"
              class="mt-1 block w-full"
              autocomplete="title"
            />
            <InputError :message="form.errors.title" class="mt-2" />
          </div>

          <div class="mb-4">
            <input
              ref="audioInput"
              type="file"
              class="hidden"
              accept="audio/aac,audio/mpeg,audio/mp4,audio/ogg,audio/wav,audio/x-ms-wma"
              @change="updateAudioPreview"
            >
            <InputLabel for="audio" value="Audio File" />
            <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="audioInput.click()">
              Select an audio file
            </SecondaryButton>
            <div v-if="audioPreview" class="mt-2">
              <Player :src="audioPreview ?? ''" :enable-repeat="false" :autoload="true" />
            </div>
            <InputError v-if="form.errors.audio?.length > 0" :message="form.errors.audio" class="mt-2" />
          </div>

          <div class="mb-4">
            <input
              ref="transcriptionInput"
              type="file"
              class="hidden"
              accept="application/vnd.recordare.musicxml+xml,application/vnd.recordare.musicxml-portable+xml,application/vnd.recordare.musicxml,application/xml"
              @change="updateTranscriptionPreview"
            >
            <InputLabel for="transcription" value="Transcription File" />
            <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="transcriptionInput.click()">
              Select a score/tab file
            </SecondaryButton>

            <div v-if="transcriptionPreview" class="mt-2">
              <TabViewer :transcription="transcriptionPreview ?? ''" />
            </div>
            <InputError v-if="form.errors.transcription?.length > 0" :message="form.errors.transcription" class="mt-2" />
          </div>

          <div class="mb-4">
            <InputLabel for="tempo" value="Tempo" />
            <TextInput
              id="tempo"
              v-model="form.tempo"
              type="text"
              class="mt-1 block w-full"
              autocomplete="tempo"
            />
            <InputError :message="form.errors.tempo" class="mt-2" />
          </div>

          <div class="mb-4">
            <InputLabel for="tags" value="Tags" />
            <TagSelector class="mt-1 block w-full" id="tags" v-model="form.tags" />
          </div>

          <div class="mb-4">
            <InputLabel for="amp_settings" value="Amp Settings" />
            <AmpSettings class="mt-1" id="amp_settings" v-model="form.amp_settings" />
          </div>

          <div class="flex items-center justify-end mt-4 text-right">
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
              Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Update
            </PrimaryButton>
          </div>
        </Card>
      </form>
    </div>
  </AppLayout>
</template>