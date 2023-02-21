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
  AudioSourceSelector,
} from '~~/Components';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import route from 'ziggy-js';
import { AmpSetting } from '~~/types';

const form = useForm<{
  _method: string;
  title: string;
  transcription: string | null; // <- export musicxml from tab or score in other program for now
  audio: File | null;
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

// audio preview handling
const audioPreview = ref<string | null>(null);

const updateAudioPreview = (inputValue: File | null) => {
  audioPreview.value = null; // reset value to force reload

  if (!inputValue) return;

  const reader = new FileReader();

  reader.onload = (e: ProgressEvent<FileReader>) => {
    audioPreview.value = e.target?.result as string; // encoded as Base64
  };

  reader.readAsDataURL(inputValue);
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
            <InputLabel for="audio" value="Audio" />
            <AudioSourceSelector v-model="form.audio" @update:modelValue="updateAudioPreview" />
          </div>

          <div class="mb-4">
            <div v-if="audioPreview" class="mt-2">
              <AudioPlayer
                :src="audioPreview ?? ''"
                :enable-repeat="false"
                :autoload="true"
              />
            </div>
            <InputError
              v-if="form.errors.audio?.length"
              :message="form.errors.audio"
              class="mt-2"
            />
          </div>

          <div class="mb-4">
            <input
              ref="transcriptionInput"
              type="file"
              class="hidden"
              accept=".musicxml,application/vnd.recordare.musicxml+xml,application/vnd.recordare.musicxml-portable+xml,application/vnd.recordare.musicxml,application/xml"
              @change="updateTranscriptionPreview"
            />
            <InputLabel for="transcription" value="Transcription File" />
            <SecondaryButton
              class="mt-2 mr-2"
              type="button"
              @click.prevent="transcriptionInput.click()"
            >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
              class="w-5 h-5 mr-2"
            >
              <path
                fill-rule="evenodd"
                d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                clip-rule="evenodd"
              />
            </svg>
            Select a score/tab file
            </SecondaryButton>

            <div v-if="transcriptionPreview" class="mt-2">
              <TabViewer :transcription="transcriptionPreview ?? ''" />
            </div>
            <InputError
              v-if="form.errors.transcription?.length"
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
