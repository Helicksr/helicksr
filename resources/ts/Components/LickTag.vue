<script setup lang="ts">
import { computed, PropType } from 'vue';
import colors from 'tailwindcss/colors';

const props = defineProps({
  tag: {
    type: String,
    required: true,
  },
});

const disallowedColors = ["inherit", "current", "transparent", "black", "white"];

const derivedColorStyle = computed(() => {

  const availableColorsKeys = Object.keys(colors).filter(c => !disallowedColors.includes(c));
  const colorsKeyNumber = (props.tag.length <= availableColorsKeys.length ? props.tag.length : availableColorsKeys.length);
  const selectedKey = availableColorsKeys[colorsKeyNumber] as keyof typeof colors;
  const selectedColor = colors[selectedKey];
  
  return { "background-color": selectedColor[700], color: selectedColor[100] };
});

</script>

<template>
  <span
    class="
      px-2
      py-1
      font-semibold
      leading-tight
      rounded-full
      inline
    "
    :style="derivedColorStyle"
  >
    {{ tag }}
  </span>
</template>