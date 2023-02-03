<script setup lang="ts">
import { PageTitle, SectionBorder } from '~~/Components';
import { AppLayout } from '~~/Layouts';
import {
  DeleteTeamForm,
  TeamMemberManager,
  UpdateTeamNameForm,
} from './Partials';
import { PropType } from 'vue';

defineProps({
  team: {
    type: Object as PropType<App.Models.Team>,
    required: true,
  },
  availableRoles: {
    type: Array as PropType<App.Models.Role[]>,
    default: () => [],
  },
  permissions: {
    type: Object as PropType<App.Models.UserPermissions>,
    required: true,
  },
});
</script>

<template>
  <AppLayout title="Team Settings">
    <template #header>
      <PageTitle>Team Settings</PageTitle>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <UpdateTeamNameForm :team="team" :permissions="permissions" />

        <TeamMemberManager
          class="mt-10 sm:mt-0"
          :team="team"
          :available-roles="availableRoles"
          :user-permissions="permissions"
        />

        <template v-if="permissions.canDeleteTeam && !team.personal_team">
          <SectionBorder />

          <DeleteTeamForm class="mt-10 sm:mt-0" :team="team" />
        </template>
      </div>
    </div>
  </AppLayout>
</template>
