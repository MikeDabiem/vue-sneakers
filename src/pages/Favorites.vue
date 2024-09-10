<script setup>
import { inject, onMounted, ref } from 'vue'
import axios from 'axios'
import CardList from '@/components/CardList.vue'

const apiServer = inject('apiServer')

const favorites = ref([])

const { cartItems } = inject('cartProps')

onMounted(async () => {
  try {
    const { liked_items, cart } = (await axios.get(apiServer + '?getLikedItems=true')).data
    const cartArr = Object.values(cart)

    favorites.value = liked_items.map((item) => ({
      ...item,
      isLiked: true,
      isAdded: cartArr && !!cartArr.find((added) => added.id === item.id)
    }))

    cartItems.value = cartArr
  } catch (err) {
    console.log(err)
  }
})
</script>

<template>
  <h1>Мои закладки</h1>

  <div class="mt-10">
    <CardList :items="favorites" />
  </div>
</template>
