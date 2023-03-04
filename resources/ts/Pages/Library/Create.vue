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
  TagSelector,
  AmpSettings,
  AudioSourceSelector,
  AudioPreview,
  ScoreSourceSelector,
} from '~~/Components';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import route from 'ziggy-js';
import { AmpSetting } from '~~/types';

const form = useForm<{
  _method: string;
  title: string;
  transcription: string | null; // <- export musicxml from tab or score in other program for now
  audio: File | Blob | null;
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
  form.post(route('library.store'), {
    errorBag: 'submit',
    preserveScroll: true,
  });
};

// const selectedAudioTab: 'disk' | 'record' = 'disk';

// audio preview handling
const enableRemove = computed(
  () => form.audio instanceof File || form.audio instanceof Blob
);
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
            <AudioSourceSelector
              v-model="form.audio"
              :enable-remove="enableRemove"
            />
          </div>

          <div class="mb-4">
            <AudioPreview v-model="form.audio" class="mt-2" />
            <InputError
              v-if="form.errors.audio?.length"
              :message="form.errors.audio"
              class="mt-2"
            />
          </div>

          <div class="mb-4">
            <InputLabel for="transcription" value="Transcription File" />
            <ScoreSourceSelector v-model="form.transcription" />
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
