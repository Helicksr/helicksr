<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import {
  AudioPlayer,
  AppCard,
  DangerButton,
  DialogModal,
  FormattedDateTime,
  LickTag,
  PageTitle,
  SecondaryButton,
  TabViewer,
} from '~~/Components';
import route from 'ziggy-js';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { PropType, ref } from 'vue';
import { Lick } from '~~/types';

const props = defineProps({
  lick: {
    type: Object as PropType<Lick>,
    required: true,
  },
  canEdit: {
    type: Boolean,
    default: false,
  },
  canDelete: {
    type: Boolean,
    default: false,
  },
  author: {
    type: String,
    default: '',
  },
});

const confirmingDeletion = ref(false);

const form = useForm({});

const confirmDeletion = () => {
  confirmingDeletion.value = true;
};

const deleteLick = () => {
  form.delete(route('library.destroy', { lick: props.lick }), {
    onSuccess: () => closeDeleteModal(),
  });
};

const closeDeleteModal = () => {
  confirmingDeletion.value = false;
};
</script>

<template>
  <AppLayout :title="lick.title">
    <template #header>
      <Link
        v-if="canEdit"
        :href="route('library.edit', { lick })"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition float-right"
      >
        Edit
      </Link>
      <PageTitle>{{ lick.title }}</PageTitle>
    </template>
    <div class="max-w-7xl mx-auto sm:my-4">
      <div v-if="lick.audio_file_url" class="flex flex-row">
        <div class="basis-full">
          <AppCard>
            <AudioPlayer :src="lick.audio_file_url ?? ''" />
          </AppCard>
        </div>
      </div>
      <div class="grid sm:grid-cols-4 gap-0 sm:gap-4 sm:mt-4">
        <div class="col-span-4 sm:col-span-1">
          <AppCard v-if="lick.amp_settings.length > 0">
            Amp Settings:
            <ul class="list-disc ml-4">
              <li v-for="setting in lick.amp_settings" :key="setting.knob">
                {{ setting.knob }}: {{ setting.value }}
              </li>
            </ul>
          </AppCard>
          <AppCard :class="{ 'sm:mt-4': lick.amp_settings.length > 0 }">
            <ul class="list-disc sm:ml-4">
              <li v-if="author">Author: {{ author }}</li>
              <li>Original tempo: {{ lick.tempo }} BPM</li>
              <li>Submitted: <FormattedDateTime :date="lick.created_at" /></li>
            </ul>
          </AppCard>
          <AppCard v-if="lick.tags.length > 0" class="sm:mt-4">
            <p>
              Tags:
              <template v-for="tag in lick.tags" :key="tag">
                <LickTag class="text-xs" :tag="tag" />{{ ' ' }}
              </template>
            </p>
          </AppCard>
          <AppCard v-if="canDelete" class="sm:mt-4">
            <DangerButton @click="confirmDeletion"> Delete Lick </DangerButton>

            <DialogModal :show="confirmingDeletion" @close="closeDeleteModal">
              <template #title> Delete Account </template>

              <template #content>
                Are you sure you want to delete this Lick?
              </template>

              <template #footer>
                <SecondaryButton @click="closeDeleteModal">
                  Cancel
                </SecondaryButton>

                <DangerButton
                  class="ml-3"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  @click="deleteLick"
                >
                  Delete
                </DangerButton>
              </template>
            </DialogModal>
          </AppCard>
        </div>
        <div v-if="lick.transcription" class="col-span-4 sm:col-span-3">
          <AppCard>
            <TabViewer :transcription="lick.transcription" />
          </AppCard>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
