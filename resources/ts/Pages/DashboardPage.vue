<script setup lang="ts">
import { AppLayout } from '~~/Layouts';
import { AppCard, PageTitle, PageSubtitle } from '~~/Components';
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <PageTitle>Dashboard</PageTitle>
    </template>

    <div class="sm:my-4">
      <AppCard class="max-w-7xl mx-auto">
        <PageSubtitle>Library</PageSubtitle>
        <div>Most visited licks here</div>

        <PageSubtitle>Shared with me</PageSubtitle>
        <div>Recently shared here</div>
      </AppCard>
    </div>
  </AppLayout>
</template>
