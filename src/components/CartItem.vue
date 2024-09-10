<script setup>
import { inject } from 'vue'

defineProps({
  id: Number,
  title: String,
  price: Number,
  img: String,
  item: Object
})

const { handleCartItem } = inject('cartProps')
</script>

<template>
  <div class="flex items-center border border-slate-200 p-4 rounded-xl gap-4">
    <img class="w-16 h-16" :src="img" :alt="title" />
    <div class="flex flex-col w-full">
      <p>{{ title }}</p>

      <div class="flex justify-between mt-2">
        <b>{{ price }} $</b>
        <img
          @click="handleCartItem(item)"
          class="opacity-40 hover:opacity-100 transition cursor-pointer"
          src="/close.svg"
          alt="Close"
        />
      </div>
    </div>
  </div>
</template>
