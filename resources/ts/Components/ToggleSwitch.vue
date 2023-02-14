<script setup lang="ts">
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';

defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: boolean): void;
}>();

const update = (checked: boolean) => {
  emits(changeEmitName, checked);
};
</script>

<template>
  <SwitchGroup>
    <div class="flex items-center">
      <SwitchLabel class="mr-4"><slot /></SwitchLabel>
      <Switch
        :model-value="modelValue"
        :class="modelValue ? 'bg-gray-800' : 'bg-gray-200'"
        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2"
        @update:model-value="update"
      >
        <span
          :class="modelValue ? 'translate-x-6' : 'translate-x-1'"
          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
        />
      </Switch>
    </div>
  </SwitchGroup>
</template>
