<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '~~/Components/Card.vue';
import PageTitle from '~~/Components/PageTitle.vue';
import PageSubtitle from '~~/Components/PageSubtitle.vue';
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <PageTitle>Dashboard</PageTitle>
    </template>

    <div class="sm:py-12">
      <Card class="max-w-7xl mx-auto">
        <PageSubtitle>Library</PageSubtitle>
        <div>
          Most visited licks here
        </div>

        <PageSubtitle>Shared with me</PageSubtitle>
        <div>
          Recently shared here
        </div>
      </Card>
    </div>
  </AppLayout>
</template>
