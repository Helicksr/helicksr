<script setup lang="ts">
import { PropType } from 'vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';

const props = defineProps({
  modelValue: {
    type: Array as PropType<App.Models.AmpSetting[]>,
    default: () => ([]),
  },
});

const emits = defineEmits<{(eventName: string, newValue: typeof props.modelValue): void }>();

const updateValue = (newValue: typeof props.modelValue) => {
  emits('update:modelValue', newValue);
};

const addNewSetting = () => {
  updateValue(props.modelValue.concat([{ knob: '', value: '' }]));
};

const removeSetting = (settingsKey: number) => {
  if (settingsKey >= props.modelValue.length) return;

  const firstPart = props.modelValue.slice(0, settingsKey);
  const secondPart = props.modelValue.slice(settingsKey + 1);
  updateValue(firstPart.concat(secondPart));
};

const updateSettingKnob = (newSettingsKey: string, settingsKey: number) => {
  const newSetting = {
    knob: newSettingsKey,
    value: props.modelValue[settingsKey].value,
  };
  const firstPart = props.modelValue.slice(0, settingsKey).concat([newSetting]);
  const secondPart = props.modelValue.slice(settingsKey + 1);
  updateValue(firstPart.concat(secondPart));
};

const updateSettingValue = (newSettingsValue: string, settingsKey: number) => {
  const newSetting = {
    knob: props.modelValue[settingsKey].knob,
    value: newSettingsValue,
  };
  const firstPart = props.modelValue.slice(0, settingsKey).concat([newSetting]);
  const secondPart = props.modelValue.slice(settingsKey + 1);
  updateValue(firstPart.concat(secondPart));
};

</script>

<template>
  <div>
    <div
      v-for="(setting, settingKey) in modelValue"
      :key="settingKey"
      class="flex"
    >
      <div class="mb-2 mr-2 flex-initial w-1/4">
        <TextInput
          id="name"
          :model-value="setting.knob"
          @input="updateSettingKnob($event.target.value, settingKey)"
          type="text"
          class="w-full"
          placeholder="Knob"
        />
      </div>
      <div class="mb-2 mr-2 flex-initial w-3/4">
        <TextInput
          id="name"
          :model-value="setting.value ?? ''"
          @input="updateSettingValue($event.target.value, settingKey)"
          type="text"
          class="w-full"
          placeholder="Value"
        />
      </div>
      <div class="flex-none">
        <SecondaryButton type="button" class="py-2 px-2 mt-1 rounded-full" @click="removeSetting(settingKey)">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            stroke="currentColor"
            class="w-4 h-4 stroke-2 fill-none"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </SecondaryButton>
      </div>
    </div>
    <SecondaryButton @click="addNewSetting">Add</SecondaryButton>
  </div>
</template>