<script setup>
import { inject } from 'vue'

defineProps({
  id: Number,
  title: String,
  price: Number,
  img: String,
  isLiked: Boolean,
  isAdded: Boolean,
  item: Object
})

const onClickLike = inject('onClickLike')
const { handleCartItem } = inject('cartProps')
</script>

<template>
  <div
    class="relative flex flex-col w-full border border-slate-100 rounded-xl p-8 cursor-pointer transition hover:shadow-xl hover:transform hover:-translate-y-2"
  >
    <div @click="onClickLike(item)" class="absolute top-8 left-8">
      <img :src="isLiked ? '/like-1.svg' : '/like-2.svg'" alt="Favorite" />
    </div>
    <img :src="img" class="w-full" :alt="title" />
    <p class="mb-5">{{ title }}</p>
    <div class="flex justify-between items-end mt-auto">
      <div class="flex flex-col gap-2">
        <span class="text-slate-200">Цена:</span>
        <span class="font-bold">{{ price }} $</span>
      </div>
      <img
        @click="handleCartItem(item)"
        :src="!isAdded ? '/plus.svg' : '/checked.svg'"
        alt="Plus"
      />
    </div>
  </div>
</template>
