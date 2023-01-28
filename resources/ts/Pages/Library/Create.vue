<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import Card from '~~/Components/Card.vue';
import InputError from '@/Components/InputError.vue';
import PageTitle from '~~/Components/PageTitle.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import ActionMessage from '~~/Components/ActionMessage.vue';
import PrimaryButton from '~~/Components/PrimaryButton.vue';
import SecondaryButton from '~~/Components/SecondaryButton.vue';
import Player from '~~/Components/AudioPlayer/Player.vue';
import { ref } from 'vue';
import TabViewer from '~~/Components/TabViewer.vue';

const form = useForm({
  _method: 'POST',
  title: '',
  transcription: null, // <- export musicxml from tab or score in other program for now
  audio: null,
  tempo: '', // <- detected from audio? let's put a button to autodetect next to the field
  tags: [],
  amp_settings: [],
});

const submit = () => {
  form.transcription = transcriptionInput.value.files[0];
  form.audio = audioInput.value.files[0];

  form.post(route('library.store'), {
    errorBag: 'submit',
    preserveScroll: true,
    onSuccess: (result) => {
      console.log({ result });
      // redirect to recently created page
    },
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
</script>

<template>
  <AppLayout title="Library">
    <template #header>
      <PageTitle>Create a new Lick</PageTitle>
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
              @change="updateAudioPreview"
            >
            <InputLabel for="audio" value="Audio File" />
            <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="audioInput.click()">
              Select an audio file
            </SecondaryButton>
            <div v-if="audioPreview" class="mt-2">
              <Player :src="audioPreview ?? ''" :enable-repeat="false" :autoload="true" />
            </div>
          </div>

          <div class="mb-4">
            <input
              ref="transcriptionInput"
              type="file"
              class="hidden"
              @change="updateTranscriptionPreview"
            >
            <InputLabel for="transcription" value="Transcription File" />
            <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="transcriptionInput.click()">
              Select a score/tab file
            </SecondaryButton>

            <div v-if="transcriptionPreview" class="mt-2">
              <TabViewer :transcription="transcriptionPreview ?? ''" />
            </div>
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

          <div class="flex items-center justify-end mt-4 text-right">
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
              Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Create
            </PrimaryButton>
          </div>
        </Card>
      </form>
    </div>
  </AppLayout>
</template>