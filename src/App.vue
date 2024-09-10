<script setup>
import { computed, provide, ref } from 'vue'

import AppHeader from './components/AppHeader.vue'
import Drawer from './components/Drawer.vue'
import axios from 'axios'

const apiServer = 'http://localhost:8000/api/api.php'

const cartItems = ref([])

const cartOpen = ref(false)

const totalPrice = computed(() => cartItems.value.reduce((acc, item) => acc + item.price, 0))
const taxPrice = computed(() => (totalPrice.value * 0.05).toFixed(2))

const cartAction = () => {
  cartOpen.value = !cartOpen.value
}

const handleCartItem = async (item) => {
  try {
    const cartItemId = {
      cartItem: item.id
    }
    await axios.post(apiServer, cartItemId)

    if (item.isAdded) {
      cartItems.value = cartItems.value.filter((cartItem) => cartItem.id !== item.id)
    } else {
      cartItems.value.push(item)
    }
    item.isAdded = !item.isAdded
  } catch (err) {
    console.log(err)
  }
}

const onClickLike = async (item) => {
  try {
    const likeId = {
      like: item.id
    }
    await axios.post(apiServer, likeId)

    item.isLiked = !item.isLiked
  } catch (err) {
    console.log(err)
  }
}

provide('apiServer', apiServer)

provide('onClickLike', onClickLike)

provide('cartProps', {
  cartItems,
  cartAction,
  handleCartItem
})
</script>

<template>
  <drawer v-if="cartOpen" :total-price="totalPrice" :tax-price="taxPrice" />
  <div class="bg-white w-4/5 m-auto rounded-xl shadow-xl mt-14">
    <app-header :total-price="totalPrice" @cart-action="cartAction" />

    <div class="p-10">
      <router-view></router-view>
    </div>
  </div>
</template>
