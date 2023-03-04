<script setup lang="ts">
import { PropType } from 'vue';
import { SecondaryButton } from '~~/Components';

defineProps({
  modelValue: {
    type: [String, null] as PropType<string | null | undefined>,
    default: undefined,
  },

  enableRemove: {
    type: Boolean,
    default: false,
  },

  enableRestore: {
    type: Boolean,
    default: false,
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: string | null | undefined): void;
}>();

const updateValue = (newValue: Event | string | null | undefined) => {
  if (newValue instanceof Event) {
    const target = newValue.target as HTMLInputElement;
    emits(changeEmitName, target.value);
    return;
  }
  emits(changeEmitName, newValue);
};
</script>

<template>
  <div>
    <SecondaryButton
      v-if="enableRemove"
      class="mt-2 mr-2"
      type="button"
      @click="updateValue(null)"
    >
      Remove
    </SecondaryButton>
    <SecondaryButton
      v-if="enableRestore"
      class="mt-2 mr-2"
      type="button"
      @click="updateValue(undefined)"
    >
      Restore
    </SecondaryButton>
    <textarea
      rows="10"
      class="w-full mt-2 border-gray-300 dark:border-gray-400 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm dark:bg-gray-300 dark:text-black"
      :value="modelValue ?? ''"
      @input="updateValue"
    />
  </div>
</template>
