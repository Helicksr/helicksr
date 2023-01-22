<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { PropType } from 'vue';
import PageTitle from '~~/Components/PageTitle.vue';

defineProps({
  lick: {
    type: Object as PropType<App.Models.Lick>,
    required: true,
  },
});
</script>

<template>
  <AppLayout title="Library">
    <template #header>
      <PageTitle>{{ lick.title }}</PageTitle>
    </template>
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-row">
        <div class="basis-full">
          first, audio player with amplitude visualization kinda like soundcloud
        </div>
      </div>
      <div class="flex flex-row">
        <div class="basis-1/5">
          on the left a column block with some basic info
        </div>
        <div class="basis-4/5">
          display tab/score
        </div>
      </div>
    </div>
  </AppLayout>
</template>