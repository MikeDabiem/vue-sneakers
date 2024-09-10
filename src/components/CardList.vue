<script setup>
import Card from './Card.vue'

defineProps({
  items: Array
})
</script>

<template>
  <div class="grid grid-cols-4 gap-5" v-auto-animate>
    <Card
      v-for="item in items"
      :key="item.id"
      :id="item.id"
      :title="item.title"
      :price="item.price"
      :img="item.imageUrl"
      :isLiked="item.isLiked"
      :is-added="item.isAdded"
      :item="item"
    />
  </div>
</template>
