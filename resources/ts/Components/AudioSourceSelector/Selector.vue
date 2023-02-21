<script setup lang="ts">
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { computed, PropType, ref } from 'vue';
import { InputError } from '~~/Components';
import { AudioFileSelector, AudioRecorder } from '.';

const props = defineProps({
  modelValue: {
    type: [File, Blob] as PropType<File | Blob | null>, // should accept url, file or blob
    default: null,
  },

  errors : {
    type: String,
    default: '',
  },
});

const changeEmitName = 'update:modelValue';

const emits = defineEmits<{
  (eventName: typeof changeEmitName, newValue: File | Blob | null): void;
}>();

const fileModelValue = computed(() => props.modelValue instanceof Blob ? null : props.modelValue);

const blobModelValue = computed(() => props.modelValue instanceof File ? null : props.modelValue);

const updateValue = (newValue: File | Blob | null) => {
  emits(changeEmitName, newValue);
};
</script>

<template>
  <TabGroup>
    <TabList>
      <Tab
        class="
          inline-flex
          items-center
          px-8
          py-1
          border-b-2
          font-medium
          focus:outline-none

          ui-not-selected:border-transparent
          ui-not-selected:text-gray-400
          ui-not-selected:hover:text-gray-700
          ui-not-selected:hover:border-gray-300
          ui-not-selected:dark:text-gray-300

          ui-selected:border-gray-700
          ui-selected:text-gray-700
          ui-selected:dark:text-gray-100
        "
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
        Select a file
      </Tab>
      <Tab
        class="
          inline-flex
          items-center
          px-8
          py-1
          border-b-2
          font-medium
          focus:outline-none

          ui-not-selected:border-transparent
          ui-not-selected:text-gray-400
          ui-not-selected:hover:text-gray-700
          ui-not-selected:hover:border-gray-300
          ui-not-selected:dark:text-gray-300

          ui-selected:border-gray-700
          ui-selected:text-gray-700
          ui-selected:dark:text-gray-100
        "
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          class="w-5 h-5 mr-1"
        >
          <path d="M7 4a3 3 0 016 0v6a3 3 0 11-6 0V4z" />
          <path d="M5.5 9.643a.75.75 0 00-1.5 0V10c0 3.06 2.29 5.585 5.25 5.954V17.5h-1.5a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-1.5v-1.546A6.001 6.001 0 0016 10v-.357a.75.75 0 00-1.5 0V10a4.5 4.5 0 01-9 0v-.357z" />
        </svg>
        Record
      </Tab>
    </TabList>
    <TabPanels>
      <TabPanel>
        <AudioFileSelector
          :model-value="fileModelValue"
          @update:modelValue="updateValue"
        />
      </TabPanel>
      <TabPanel>
        <AudioRecorder
          :model-value="blobModelValue"
          @update:modelValue="updateValue"
        />
      </TabPanel>
    </TabPanels>
  </TabGroup>
</template>