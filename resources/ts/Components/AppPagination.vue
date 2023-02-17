<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  total: {
    type: Number,
    required: true,
  },

  perPage: {
    type: Number,
    required: true,
  },

  currentPage: {
    type: Number,
    required: true,
  },

  // the number of buttons allowed next to start, end and current page button
  margin: {
    type: Number,
    default: 2,
  },
});

defineEmits<{
  (e: 'changePage', page: number): void;
}>();

const lastPage = computed(() => Math.ceil(props.total / props.perPage));

const firstItem = computed(() => (props.currentPage - 1) * props.perPage + 1);

const lastItem = computed(() =>
  Math.min(props.currentPage * props.perPage, props.total)
);

const previousButtonDisabled = computed(() => props.currentPage === 1);

const nextButtonDisabled = computed(() => props.currentPage === lastPage.value);

const shouldPrintButton = (page: number) => {
  // should only print if close current page, start or end
  return (
    Math.abs(page - props.currentPage) <= props.margin ||
    page <= props.margin ||
    lastPage.value - page < props.margin
  );
};

const shouldPrintEllipsis = (page: number) => {
  // should only print in position of start page + margin or last page minus margin
  if (page < props.currentPage) {
    return page === props.margin + 1;
  }

  if (page > props.currentPage) {
    return lastPage.value - page === props.margin;
  }

  return false;
};
</script>

<template>
  <div
    class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
  >
    <span class="flex items-center col-span-3">
      Showing {{ firstItem }}-{{ lastItem }} of {{ total }}
    </span>
    <span class="col-span-2"></span>
    <!-- Pagination -->
    <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
      <nav aria-label="Table navigation">
        <ul class="inline-flex items-center">
          <li>
            <Link
              :href="previousButtonDisabled ? '#' : `?page=${currentPage - 1}`"
              class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple"
              aria-label="Previous"
            >
              <svg
                aria-hidden="true"
                class="w-4 h-4 fill-current"
                viewBox="0 0 20 20"
              >
                <path
                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                  clip-rule="evenodd"
                  fill-rule="evenodd"
                ></path>
              </svg>
            </Link>
          </li>

          <template v-for="page in lastPage" :key="page">
            <li v-if="shouldPrintButton(page)">
              <Link
                :href="`?page=${page}`"
                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                :class="{
                  'text-white transition-colors duration-150 bg-gray-600 border border-r-0 border-gray-600':
                    page === currentPage,
                }"
              >
                {{ page }}
              </Link>
            </li>
            <li v-else-if="shouldPrintEllipsis(page)">
              <span class="px-3 py-1">...</span>
            </li>
          </template>

          <li>
            <Link
              :href="nextButtonDisabled ? '#' : `?page=${currentPage + 1}`"
              class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple"
              aria-label="Next"
            >
              <svg
                class="w-4 h-4 fill-current"
                aria-hidden="true"
                viewBox="0 0 20 20"
              >
                <path
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                  fill-rule="evenodd"
                ></path>
              </svg>
            </Link>
          </li>
        </ul>
      </nav>
    </span>
  </div>
</template>
