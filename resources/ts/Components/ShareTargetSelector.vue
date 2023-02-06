<script setup lang="ts">
import Multiselect from '@vueform/multiselect';
import { PropType } from 'vue';
import { Team, User } from '~~/types';

// selected users and teams
export type ShareTargetSelectorInputType = {
  users: User[];
  teams: Team[];
};

const props = defineProps({
  modelValue: {
    type: Object as PropType<ShareTargetSelectorInputType>,
    default: () => ({
      users: [],
      teams: [],
    }),
  },
});

type MultiselectOption<T> = {
  label: string; // label displayed to the user
  value: {
    type: string; // flag to distinguish accompanying object i.e 'user' or 'team'
    object: T; // actual User or Team object
  };
};

type MultiselectGroupOption<T> = {
  label: string; // label for group separator
  options: MultiselectOption<T>[];
};

const userToOption: (user: User) => MultiselectOption<User> = (user: User) => ({
  label: user.name + `(${user.email})`,
  value: {
    type: 'user',
    object: user,
  },
});

const teamToOption: (team: Team) => MultiselectOption<Team> = (team: Team) => ({
  label: team.name,
  value: {
    type: 'team',
    object: team,
  },
});

const modelValueAsObjectArray: MultiselectOption<User | Team>[] = [
  ...props.modelValue.users.map(userToOption),
  ...props.modelValue.teams.map(teamToOption),
];

const inputEmitName = 'update:modelValue';

const emits = defineEmits<{
  (
    eventName: typeof inputEmitName,
    emittedValue: ShareTargetSelectorInputType
  ): void;
}>();

const onInput = (newValues: MultiselectOption<User | Team>[]) => {
  const result: ShareTargetSelectorInputType = {
    users: [],
    teams: [],
  };

  newValues.forEach((newValue) => {
    if (newValue.value.type === 'user') {
      result.users.push(newValue.value.object as User);
    }
    if (newValue.value.type === 'team') {
      result.teams.push(newValue.value.object as Team);
    }
  });

  emits(inputEmitName, result);
};

// fetch users and teams for autocomplete
const fetchTargets: (
  search: string
) => Promise<MultiselectGroupOption<User | Team>[]> = async (
  search: string
) => {
  const response = await fetch(
    '/share-targets?' + new URLSearchParams({ search })
  );
  const data: { users: User[]; teams: Team[] } = response.ok
    ? await response.json()
    : { users: [], teams: [] };

  return [
    {
      label: 'Users',
      options: data.users.map(userToOption),
    },
    {
      label: 'Teams',
      options: data.teams.map(teamToOption),
    },
  ];
};
</script>

<template>
  <Multiselect
    :model-value="modelValueAsObjectArray"
    placeholder="search for users or teams"
    mode="tags"
    :close-on-select="false"
    :filter-results="false"
    :min-chars="1"
    :resolve-on-load="false"
    :delay="100"
    :searchable="true"
    :create-option="true"
    :options="fetchTargets"
    :object="true"
    :groups="true"
    @input="onInput"
  />
</template>

<style>
@import '../../../node_modules/@vueform/multiselect/themes/tailwind.css';
</style>
