<script setup lang="ts">
// import { AppLayout } from '~~/Layouts';
import { Head, Link } from '@inertiajs/vue3';
import route from 'ziggy-js';
import { LandingNavbar } from '~~/Components';
</script>

<template>
  <div>
    <Head title="Welcome" />
    <LandingNavbar />

    <div class="pt-6 md:pt-16 max-w-7xl mx-auto">
      <div
        class="container px-3 mx-auto flex flex-wrap flex-col md:flex-row items-center"
      >
        <!--Left Col-->
        <div
          class="flex flex-col w-full md:w-2/5 justify-center items-start text-center md:text-left"
        >
          <p class="uppercase tracking-loose w-full">
            Lost that cool lick from yesterdays' practice jam?
          </p>
          <h1 class="my-4 text-5xl font-bold leading-tight">
            Never let those flashes of inspiration get away again!
          </h1>
          <p class="leading-normal text-2xl mb-8">
            Preserving your musical ideas, easier tan ever!
          </p>
          <Link
            :href="route('register')"
            class="text-lg inline-flex items-center px-4 py-2 dark:bg-gray-500 bg-gray-800 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
            >Get Started</Link
          >
        </div>
        <!--Right Col-->
        <div class="w-full md:w-3/5 py-6 text-center">
          <!--
            <img class="w-full md:w-4/5 z-50" src="hero.png" />
          -->
        </div>
      </div>
    </div>
  </div>
</template>
