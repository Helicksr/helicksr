<script setup lang="ts">
import { Link } from '@inertiajs/inertia-vue3';
import { computed, PropType } from 'vue';

const props = defineProps({
  href: {
    type: String,
    default: '',
  },
  as: {
    type: String as PropType<'a' | 'button'>,
    default: 'button',
  },
});

const isAnchor = computed(() => props.as === 'a');

const isButton = computed(() => props.as === 'button');

const defaultClasses = [
  'block',
  'px-4',
  'py-2',
  'leading-5',
  'text-gray-700',
  'hover:bg-gray-100',
  'focus:outline-none',
  'focus:bg-gray-100',
  'transition',

  'dark:text-gray-300',
  // "dark:bg-gray-500",
  'dark:hover:bg-gray-600',
  'dark:hover:text-gray-200',
  'dark:focus:bg-gray-800',
  'dark:active:bg-gray-800',
];

const buttonClasses = ['w-full', 'text-left'];
</script>

<template>
  <div>
    <button
      v-if="isButton"
      type="submit"
      :class="defaultClasses.concat(buttonClasses)"
    >
      <slot />
    </button>

    <a v-else-if="isAnchor" :href="href" :class="defaultClasses">
      <slot />
    </a>

    <Link v-else :href="href ?? ''" :class="defaultClasses">
      <slot />
    </Link>
  </div>
</template>
