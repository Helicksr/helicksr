<script setup lang="ts">
import { PropType } from 'vue';
import LickTag from './LickTag.vue';
import FormattedDateTime from './FormattedDateTime.vue';
import { Link } from '@inertiajs/inertia-vue3';
import route from 'ziggy-js';

const props = defineProps({
  licks: {
    type: Array as PropType<App.Models.Lick[]>,
    default: () => [],
  },
});
</script>

<template>
  <div class="
    w-full
    bg-white
    dark:bg-gray-500
    shadow-md
    overflow-hidden
    sm:rounded-lg
  ">
    <div class="w-full overflow-x-auto">
      <table class="w-full px-4 py-3 sm:px-6 sm:py-4 whitespace-no-wrap">
        <thead>
          <tr class="
            text-sm
            font-semibold
            tracking-wide
            text-left
            uppercase
            border-b
            dark:border-gray-400
          ">
            <th class="px-4 py-3">Title</th>
            <th class="px-4 py-3">Length</th>
            <th class="px-4 py-3">Date</th>
            <th class="px-4 py-3">Tags</th>
          </tr>
        </thead>
        <tbody class="
          divide-y
          dark:divide-gray-400
        ">
          <tr v-for="lick in licks" :key="lick.id">
            <td class="px-4 py-3">
              <Link :href="route('library.show', lick.id)">
                {{ lick.title }}
              </Link>
            </td>
            <td class="px-4 py-3">
              {{ lick.length }}
            </td>
            <td class="px-4 py-3">
              <FormattedDateTime :date="lick.created_at" />
            </td>
            <td class="px-4 py-3">
              <span v-if="lick.tags.length <= 0">-</span>
              <LickTag v-else class="text-xs mx-1" v-for="tag in lick.tags" :tag="tag" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>