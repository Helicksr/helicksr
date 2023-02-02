<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { PropType } from 'vue';
import Player from '~~/Components/AudioPlayer/Player.vue';
import Card from '~~/Components/Card.vue';
import FormattedDateTime from '~~/Components/FormattedDateTime.vue';
import LickTag from '~~/Components/LickTag.vue';
import PageTitle from '~~/Components/PageTitle.vue';
import TabViewer from '~~/Components/TabViewer.vue';
import route from 'ziggy-js';
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
  lick: {
    type: Object as PropType<App.Models.Lick>,
    required: true,
  },
  canEdit: {
    type: Boolean,
    default: false,
  },
  author: {
    type: String,
  },
});
</script>

<template>
  <AppLayout :title="lick.title">
    <template #header>
      <Link
        v-if="canEdit"
        :href="route('library.edit', { lick })"
        class="
          inline-flex
          items-center
          px-4
          py-2
          bg-gray-800
          border
          border-transparent
          rounded-md
          font-semibold
          text-xs
          text-white
          uppercase
          tracking-widest
          hover:bg-gray-700
          active:bg-gray-900
          focus:outline-none
          focus:border-gray-900
          focus:ring
          focus:ring-gray-300
          disabled:opacity-25
          transition
          float-right
        "
      >
        Edit
      </Link>
      <PageTitle>{{ lick.title }}</PageTitle>
    </template>
    <div class="max-w-7xl mx-auto sm:my-4">
      <div class="flex flex-row" v-if="lick.audio_file_url">
        <div class="basis-full">
          <Card>
            <Player :src="lick.audio_file_url ?? ''" />
          </Card>
        </div>
      </div>
      <div class="grid sm:grid-cols-4 gap-0 sm:gap-4 sm:mt-4">
        <div class="col-span-4 sm:col-span-1">
          <Card v-if="lick.amp_settings.length > 0">
            Amp Settings:
            <ul class="list-disc ml-4">
              <li v-for="setting in lick.amp_settings">{{ setting.knob }}: {{ setting.value }}</li>
            </ul>
          </Card>
          <Card :class="{ 'sm:mt-4': lick.amp_settings }">
            <ul class="list-disc sm:ml-4">
              <li v-if="author">Author: {{ author }}</li>
              <li>Original tempo: {{ lick.tempo }} BPM</li>
              <li>Submitted: <FormattedDateTime :date="lick.created_at" /></li>
            </ul>
          </Card>
          <Card v-if="lick.tags.length > 0" class="sm:mt-4">
            <p>Tags:
              <template v-for="tag in lick.tags">
                <LickTag class="text-xs" :tag="tag" />{{ ' ' }}
              </template>
            </p>
          </Card>
        </div>
        <div v-if="lick.transcription" class="col-span-4 sm:col-span-3">
          <Card>
            <TabViewer :transcription="lick.transcription" />
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>