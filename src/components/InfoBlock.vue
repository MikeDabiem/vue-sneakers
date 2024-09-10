<script setup>
defineProps({
  title: String,
  descr: String,
  img: String
})
</script>

<template>
  <div class="flex flex-col items-center justify-center h-full text-center w-72 mx-auto">
    <img width="80" height="80" :src="img" :alt="title" />
    <h2 class="mt-4 text-2xl font-medium">{{ title }}</h2>
    <p class="text-gray-400">{{ descr }}</p>
  </div>
</template>
