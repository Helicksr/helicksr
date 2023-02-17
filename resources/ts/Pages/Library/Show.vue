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
  PrimaryButton,
  ShareTargetSelector,
} from '~~/Components';
import route from 'ziggy-js';
import { Link, useForm } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';
import { Lick, User } from '~~/types';
import { ShareTargetSelectorInputType } from '~~/Components/ShareTargetSelector.vue';

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
  canShare: {
    type: Boolean,
    default: false,
  },
  author: {
    type: String,
    default: '',
  },
  sharedWith: {
    type: Array as PropType<User[]>,
    default: () => [],
  },
});

// Delete modal
const deleteModalVisible = ref(false);

const deleteForm = useForm({});

const showDeleteModal = () => {
  deleteModalVisible.value = true;
};

const deleteLick = () => {
  deleteForm.delete(route('library.destroy', { lick: props.lick }), {
    onSuccess: closeDeleteModal,
  });
};

const closeDeleteModal = () => {
  deleteModalVisible.value = false;
};

// Share modal
const shareModalVisible = ref(false);

const shareForm = useForm<{ shareWith: ShareTargetSelectorInputType }>({
  shareWith: {
    users: [],
    teams: [],
  },
});

const showShareModal = () => {
  shareModalVisible.value = true;
};

const closeShareModal = () => {
  shareForm.shareWith.teams = [];
  shareForm.shareWith.users = [];
  shareModalVisible.value = false;
};

const shareLick = () => {
  shareForm
    .transform((data) => ({
      share_target_users: data.shareWith.users.map((user) => user.id),
      share_target_teams: data.shareWith.teams.map((team) => team.id),
    }))
    .post(route('shared.create', { lick: props.lick }), {
      onSuccess: closeShareModal,
    });
};
</script>

<template>
  <AppLayout :title="lick.title">
    <template #header>
      <PrimaryButton v-if="canShare" class="float-right" @click="showShareModal"
        >Share</PrimaryButton
      >
      <PageTitle
        >{{ lick.title }}
        <Link
          v-if="canEdit"
          class="text-sm underline"
          :href="route('library.edit', { lick })"
          >(edit)</Link
        ></PageTitle
      >
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
            <ul class="list-disc ml-4">
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
            <DangerButton @click="showDeleteModal">Delete Lick</DangerButton>

            <DialogModal :show="deleteModalVisible" @close="closeDeleteModal">
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
                  :class="{ 'opacity-25': deleteForm.processing }"
                  :disabled="deleteForm.processing"
                  @click="deleteLick"
                  >Delete</DangerButton
                >
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

    <DialogModal :show="shareModalVisible" @close="closeShareModal">
      <template #title>Share Lick</template>

      <template #content>
        You can make this Lick available to other users with this form.
        Currently shared with:
        <ul v-if="props.sharedWith.length > 0" class="list-disc ml-4">
          <li v-for="userShared in props.sharedWith" :key="userShared.id">
            {{ userShared.name }} ({{ userShared.email }})
          </li>
        </ul>
        <ShareTargetSelector v-model="shareForm.shareWith" />
      </template>

      <template #footer>
        <SecondaryButton @click="closeShareModal">Cancel</SecondaryButton>

        <PrimaryButton
          class="ml-3"
          :class="{ 'opacity-25': shareForm.processing }"
          :disabled="shareForm.processing"
          @click="shareLick"
          >Share</PrimaryButton
        >
      </template>
    </DialogModal>
  </AppLayout>
</template>
