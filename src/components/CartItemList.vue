<script setup>
import CartItem from '@/components/CartItem.vue'
import { inject } from 'vue'

const { cartItems } = inject('cartProps')
</script>

<template>
  <div class="flex flex-col flex-1 gap-5 overflow-y-auto" v-auto-animate>
    <CartItem
      v-for="cartItem in cartItems"
      :key="cartItem.id"
      :id="cartItem.id"
      :title="cartItem.title"
      :price="cartItem.price"
      :img="cartItem.imageUrl"
      :item="cartItem"
    />
  </div>
</template>
