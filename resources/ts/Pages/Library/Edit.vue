<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import {
  ActionMessage,
  AmpSettings,
  AppCard,
  InputError,
  InputLabel,
  PageTitle,
  PrimaryButton,
  TagSelector,
  TextInput,
  AudioSourceSelector,
  AudioPreview,
  ScoreSourceSelector,
} from '~~/Components';
import { useForm } from '@inertiajs/vue3';
import { PropType } from 'vue';
import route from 'ziggy-js';
import { AmpSetting, Lick } from '~~/types';

const props = defineProps({
  lick: {
    type: Object as PropType<Lick>,
    required: true,
  },
  author: {
    type: String,
    default: '',
  },
});

const form = useForm<{
  _method: string;
  title: string;
  transcription: string | null; // <- export musicxml from tab or score in other program for now
  audio: File | Blob | null | undefined;
  tempo: string; // <- detected from audio? let's put a button to autodetect next to the field
  tags: string[];
  amp_settings: AmpSetting[];
}>({
  _method: 'PUT',
  title: props.lick.title,
  transcription: props.lick.transcription, // <- export musicxml from tab or score in other program for now
  audio: undefined,
  tempo: props.lick.tempo.toString(),
  tags: props.lick.tags,
  amp_settings: props.lick.amp_settings,
});

const submit = () => {
  form.post(route('library.update', { lick: props.lick }), {
    errorBag: 'submit',
    preserveScroll: true,
  });
};
</script>

<template>
  <AppLayout :title="lick.title">
    <template #header>
      <PageTitle>{{ lick.title }}</PageTitle>
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
              :original-audio-url="lick.audio_file_path"
            />
          </div>

          <div class="mb-4">
            <AudioPreview
              v-model="form.audio"
              class="mt-2"
              :original-url="lick.audio_file_url"
            />
            <InputError
              v-if="form.errors.audio?.length"
              :message="form.errors.audio"
              class="mt-2"
            />
          </div>

          <div class="mb-4">
            <InputLabel for="transcription" value="Transcription" />
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
              Update
            </PrimaryButton>
          </div>
        </AppCard>
      </form>
    </div>
  </AppLayout>
</template>
