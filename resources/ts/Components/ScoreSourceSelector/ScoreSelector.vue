<script setup lang="ts">
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { computed, PropType } from 'vue';
import { isValidXml } from '~~/utils';
import { ScoreFileSelector, TabTextEditor } from '.';
import { ScoreViewer } from '~~/Components';

const props = defineProps({
  modelValue: {
    type: [String, null, undefined] as PropType<string | null | undefined>,
    default: undefined,
  },
});

// eslint-disable-next-line vue/no-setup-props-destructure
const initialValue = props.modelValue; // intentionally storing initial prop value

const hadInitialValue =
  initialValue !== undefined &&
  initialValue !== null &&
  initialValue.length > 0;

const enableRestore = computed(
  () => hadInitialValue && props.modelValue !== initialValue
);

const enableRemove = computed(
  () => props.modelValue !== undefined && props.modelValue !== null
);

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: string | null | undefined): void;
}>();

const isXml = computed(() => isValidXml(props.modelValue ?? ''));

const xmlModelValue = computed(() => (isXml.value ? props.modelValue : null));

const textModelValue = computed(() => (isXml.value ? null : props.modelValue));

const updateValue = (newValue: string | null | undefined) => {
  if (newValue === undefined) {
    emits(changeEmitName, initialValue);
    return;
  }
  emits(changeEmitName, newValue);
};
</script>

<template>
  <TabGroup as="div" :default-index="isXml ? 1 : 0">
    <TabList>
      <Tab
        class="inline-flex items-center px-8 py-1 border-b-2 font-medium focus:outline-none ui-not-selected:border-transparent ui-not-selected:text-gray-400 ui-not-selected:hover:text-gray-700 ui-not-selected:hover:border-gray-300 ui-not-selected:dark:text-gray-300 ui-selected:border-gray-700 ui-selected:text-gray-700 ui-selected:dark:text-gray-100"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          class="w-5 h-5"
        >
          <path
            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"
          />
          <path
            d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"
          />
        </svg>
        Write
      </Tab>
      <Tab
        class="inline-flex items-center px-8 py-1 border-b-2 font-medium focus:outline-none ui-not-selected:border-transparent ui-not-selected:text-gray-400 ui-not-selected:hover:text-gray-700 ui-not-selected:hover:border-gray-300 ui-not-selected:dark:text-gray-300 ui-selected:border-gray-700 ui-selected:text-gray-700 ui-selected:dark:text-gray-100"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          class="w-5 h-5 mr-2"
        >
          <path
            fill-rule="evenodd"
            d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
            clip-rule="evenodd"
          />
        </svg>
        Upload XML
      </Tab>
    </TabList>
    <TabPanels>
      <TabPanel>
        <TabTextEditor
          :model-value="textModelValue"
          :enable-remove="enableRemove"
          :enable-restore="enableRestore"
          @update:model-value="updateValue"
        />
      </TabPanel>
      <TabPanel>
        <ScoreFileSelector
          :model-value="xmlModelValue"
          :enable-remove="enableRemove"
          :enable-restore="enableRestore"
          @update:model-value="updateValue"
        />
        <ScoreViewer v-if="isXml" :transcription="xmlModelValue ?? ''" />
      </TabPanel>
    </TabPanels>
  </TabGroup>
</template>
