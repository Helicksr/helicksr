<script setup lang="ts">
import SectionTitle from './SectionTitle.vue';
import Card from '@/Components/Card.vue';
</script>

<template>
  <div class="md:grid md:grid-cols-3 md:gap-6">
    <SectionTitle>
      <template #title>
        <slot name="title" />
      </template>
      <template #description>
        <slot name="description" />
      </template>
    </SectionTitle>

    <div class="mt-5 md:mt-0 md:col-span-2">
      <Card>
        <slot name="content" />
      </Card>
    </div>
  </div>
</template>
