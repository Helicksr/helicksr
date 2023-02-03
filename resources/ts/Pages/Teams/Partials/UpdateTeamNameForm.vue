<script setup lang="ts">
import { useForm } from '@inertiajs/inertia-vue3';
import {
  ActionMessage,
  FormSection,
  InputError,
  InputLabel,
  PrimaryButton,
  TextInput,
 } from '~~/Components';
import { PropType } from 'vue';
import route from 'ziggy-js';

const props = defineProps({
  team: {
    type: Object as PropType<App.Models.Team>,
    required: true,
  },
  permissions: {
    type: Object as PropType<App.Models.UserPermissions>,
    required: true,
  },
});

const form = useForm({
  name: props.team.name,
});

const updateTeamName = () => {
  form.put(route('teams.update', props.team), {
    errorBag: 'updateTeamName',
    preserveScroll: true,
  });
};
</script>

<template>
  <FormSection @submitted="updateTeamName">
    <template #title>
      Team Name
    </template>

    <template #description>
      The team's name and owner information.
    </template>

    <template #form>
      <!-- Team Owner Information -->
      <div class="col-span-6">
        <InputLabel value="Team Owner" />

        <div class="flex items-center mt-2">
          <img class="w-12 h-12 rounded-full object-cover" :src="team.owner.profile_photo_url" :alt="team.owner.name">

          <div class="ml-4 leading-tight dark:text-gray-300">
            <div>{{ team.owner.name }}</div>
            <div class="text-gray-700 dark:text-gray-400 text-sm">
              {{ team.owner.email }}
            </div>
          </div>
        </div>
      </div>

      <!-- Team Name -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="name" value="Team Name" />

        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          :disabled="! permissions.canUpdateTeam"
        />

        <InputError :message="form.errors.name" class="mt-2" />
      </div>
    </template>

    <template v-if="permissions.canUpdateTeam" #actions>
      <ActionMessage :on="form.recentlySuccessful" class="mr-3">
        Saved.
      </ActionMessage>

      <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </PrimaryButton>
    </template>
  </FormSection>
</template>
