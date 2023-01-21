<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '~~/Components/Card.vue';
import PageTitle from '~~/Components/PageTitle.vue';
import { PropType } from 'vue';
import LicksTable from '~~/Components/LicksTable.vue';

const props = defineProps({
  licks: Array as PropType<App.Models.Lick[]>,
});
</script>

<template>
  <AppLayout title="Library">
    <template #header>
      <PageTitle>Library</PageTitle>
    </template>

    <div class="sm:py-12">
      <div class="max-w-7xl mx-auto">
        <LicksTable :licks="licks" />
      </div>
    </div>
  </AppLayout>
</template>