<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import {
  DeleteUserForm,
  LogoutOtherBrowserSessionsForm,
  TwoFactorAuthenticationForm,
  UpdatePasswordForm,
  UpdateProfileInformationForm,
} from './Partials';
import { PageTitle, SectionBorder } from '~~/Components';
import { PropType } from 'vue';

defineProps({
  confirmsTwoFactorAuthentication: {
    type: Boolean,
    required: true,
  },
  sessions: {
    type: Array as PropType<App.BrowserSession[]>,
    default: () => ([]),
  },
});
</script>

<template>
  <AppLayout title="Profile">
    <template #header>
      <PageTitle>Profile</PageTitle>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <UpdateProfileInformationForm :user="$page.props.user" />

          <SectionBorder />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <UpdatePasswordForm class="mt-10 sm:mt-0" />

          <SectionBorder />
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <TwoFactorAuthenticationForm 
            :requires-confirmation="confirmsTwoFactorAuthentication"
            class="mt-10 sm:mt-0" 
          />

          <SectionBorder />
        </div>

        <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <SectionBorder />

          <DeleteUserForm class="mt-10 sm:mt-0" />
        </template>
      </div>
    </div>
  </AppLayout>
</template>
