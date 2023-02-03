<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import { PageTitle, LicksTable, Pagination } from '~~/Components';
import { PropType } from 'vue';

defineProps({
  licks: {
    type: Array as PropType<App.Models.Lick[]>,
    default: () => [],
  },

  total: {
    type: Number,
    required: true,
  },

  perPage: {
    type: Number,
    default: 10,
  },

  currentPage: {
    type: Number,
    default: 1,
  },
});
</script>

<template>
  <AppLayout title="Library">
    <template #header>
      <PageTitle>Library</PageTitle>
    </template>

    <div class="sm:my-4">
      <div class="max-w-7xl mx-auto">
        <span v-if="licks.length == 0">No results</span>
        <LicksTable v-else :licks="licks" />
      </div>
    </div>

    <Pagination
      v-if="licks.length > 0"
      :total="total"
      :per-page="perPage"
      :current-page="currentPage"
    />
  </AppLayout>
</template>
