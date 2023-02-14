<script setup lang="ts">
import { useForm } from '@inertiajs/inertia-vue3';
import route from 'ziggy-js';
import {
  ActionMessage,
  FormSection,
  InputLabel,
  PrimaryButton,
  ToggleSwitch,
} from '~~/Components';

const props = defineProps({
  onLickShared: {
    type: Boolean,
    default: false,
  },
  onAddedToGroup: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({
  _method: 'PUT',
  on_lick_shared: props.onLickShared,
  on_added_to_group: props.onAddedToGroup,
});

const updateNotificationSettings = () => {
  form.put(route('notification-settings.update'));
};
</script>

<template>
  <FormSection @submitted="updateNotificationSettings">
    <template #title>Email Notifications</template>

    <template #description>
      Select events to receive email notifications
    </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <ToggleSwitch id="on_lick_shared" v-model="form.on_lick_shared"
          ><InputLabel
            for="on_lick_shared"
            value="When a lick is shared with me"
        /></ToggleSwitch>
      </div>
      <div class="col-span-6 sm:col-span-4">
        <ToggleSwitch id="on_added_to_group" v-model="form.on_added_to_group"
          ><InputLabel
            for="on_added_to_group"
            value="When I'm added to a new Team"
        /></ToggleSwitch>
      </div>
    </template>

    <template #actions>
      <ActionMessage :on="form.recentlySuccessful" class="mr-3">
        Saved.
      </ActionMessage>

      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </FormSection>
</template>
