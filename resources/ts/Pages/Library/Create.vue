<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import {
  AppCard,
  InputError,
  PageTitle,
  InputLabel,
  TextInput,
  ActionMessage,
  PrimaryButton,
  SecondaryButton,
  AudioPlayer,
  TabViewer,
  TagSelector,
  AmpSettings,
} from '~~/Components';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import route from 'ziggy-js';
import { AmpSetting } from '~~/types';

const form = useForm<{
  _method: string;
  title: string;
  transcription: string | null; // <- export musicxml from tab or score in other program for now
  audio: null;
  tempo: string; // <- detected from audio? let's put a button to autodetect next to the field
  tags: string[];
  amp_settings: AmpSetting[];
}>({
  _method: 'POST',
  title: '',
  transcription: null, // <- export musicxml from tab or score in other program for now
  audio: null,
  tempo: '', // <- detected from audio? let's put a button to autodetect next to the field
  tags: [],
  amp_settings: [
    { knob: 'model', value: '' },
    { knob: 'treble', value: '' },
    { knob: 'bass', value: '' },
    { knob: 'presence', value: '' },
  ],
});

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      transcription: transcriptionPreview.value,
      audio: audioInput.value.files[0],
    }))
    .post(route('library.store'), {
      errorBag: 'submit',
      preserveScroll: true,
    });
};

// score/tab field handling
const transcriptionPreview = ref<string | null>(null);
const transcriptionInput = ref<any>(null); // TODO: find correct typings for this variable

const updateTranscriptionPreview = () => {
  const file = transcriptionInput.value?.files[0];

  if (!file) return;

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

  if (!file) return;

  audioPreview.value = null; // reset value to force reload

  const reader = new FileReader();

  reader.onload = (e: ProgressEvent<FileReader>) => {
    audioPreview.value = e.target?.result as string; // encoded as Base64
  };

  reader.readAsDataURL(file);
};
</script>

<template>
  <AppLayout title="Create a new Lick">
    <template #header>
      <PageTitle>Create a new Lick</PageTitle>
    </template>

    <div class="max-w-4xl mx-auto sm:my-4">
      <form @submit.prevent="submit">
        <AppCard>
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
            />
            <InputLabel for="audio" value="Audio File" />
            <SecondaryButton
              class="mt-2 mr-2"
              type="button"
              @click.prevent="audioInput.click()"
            >
              Select an audio file
            </SecondaryButton>
            <div v-if="audioPreview" class="mt-2">
              <AudioPlayer
                :src="audioPreview ?? ''"
                :enable-repeat="false"
                :autoload="true"
              />
            </div>
            <InputError
              v-if="form.errors.audio?.length > 0"
              :message="form.errors.audio"
              class="mt-2"
            />
          </div>

          <div class="mb-4">
            <input
              ref="transcriptionInput"
              type="file"
              class="hidden"
              accept="application/vnd.recordare.musicxml+xml,application/vnd.recordare.musicxml-portable+xml,application/vnd.recordare.musicxml,application/xml"
              @change="updateTranscriptionPreview"
            />
            <InputLabel for="transcription" value="Transcription File" />
            <SecondaryButton
              class="mt-2 mr-2"
              type="button"
              @click.prevent="transcriptionInput.click()"
            >
              Select a score/tab file
            </SecondaryButton>

            <div v-if="transcriptionPreview" class="mt-2">
              <TabViewer :transcription="transcriptionPreview ?? ''" />
            </div>
            <InputError
              v-if="form.errors.transcription?.length > 0"
              :message="form.errors.transcription"
              class="mt-2"
            />
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
            <TagSelector
              id="tags"
              v-model="form.tags"
              class="mt-1 block w-full"
            />
          </div>

          <div class="mb-4">
            <InputLabel for="amp_settings" value="Amp Settings" />
            <AmpSettings
              id="amp_settings"
              v-model="form.amp_settings"
              class="mt-1"
            />
          </div>

          <div class="flex items-center justify-end mt-4 text-right">
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
              Saved.
            </ActionMessage>

            <PrimaryButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Create
            </PrimaryButton>
          </div>
        </AppCard>
      </form>
    </div>
  </AppLayout>
</template>
