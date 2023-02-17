<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import {
  ActionMessage,
  AmpSettings,
  AudioPlayer,
  AppCard,
  InputError,
  InputLabel,
  PageTitle,
  PrimaryButton,
  SecondaryButton,
  TabViewer,
  TagSelector,
  TextInput,
} from '~~/Components';
import { useForm } from '@inertiajs/vue3';
import { onMounted, PropType, ref } from 'vue';
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
  audio: null;
  tempo: string; // <- detected from audio? let's put a button to autodetect next to the field
  tags: string[];
  amp_settings: AmpSetting[];
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
  form
    .transform((data) => ({
      ...data,
      transcription: transcriptionPreview.value,
      audio: audioInput.value.files[0],
    }))
    .post(route('library.update', { lick: props.lick }), {
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
                :enable-repeat="true"
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
              Update
            </PrimaryButton>
          </div>
        </AppCard>
      </form>
    </div>
  </AppLayout>
</template>
