<script setup lang="ts">
import { computed, useSlots } from 'vue';
import { AppCard, SectionTitle } from '~~/Components';

defineEmits(['submitted']);

const hasActions = computed(() => !!useSlots().actions);
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
      <form @submit.prevent="$emit('submitted')">
        <AppCard>
          <div class="grid grid-cols-6 gap-6">
            <slot name="form" />
          </div>

          <div
            v-if="hasActions"
            class="flex items-center justify-end pt-3 text-right"
          >
            <slot name="actions" />
          </div>
        </AppCard>
      </form>
    </div>
  </div>
</template>
