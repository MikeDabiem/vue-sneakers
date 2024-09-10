<script setup>
import DrawerHead from '@/components/DrawerHead.vue'
import CartItemList from '@/components/CartItemList.vue'
import { computed, inject, ref } from 'vue'
import InfoBlock from '@/components/InfoBlock.vue'
import axios from 'axios'

const apiServer = inject('apiServer')

const props = defineProps({
  totalPrice: Number,
  taxPrice: String
})

const isCreatingOrder = ref(false)
const orderId = ref(false)

const disableBtn = computed(() => (isCreatingOrder.value ? true : !props.totalPrice))

const { cartItems, cartAction } = inject('cartProps')

const createOrder = async () => {
  try {
    isCreatingOrder.value = true
    const orderItems = cartItems.value.map((item) => ({
      id: item.id,
      title: item.title,
      price: item.price
    }))

    const orderData = {
      orderItems,
      totalPrice: props.totalPrice
    }

    const { data } = await axios.post(apiServer, orderData)
    orderId.value = data

    cartItems.value = []
  } catch (err) {
    console.log(err)
  } finally {
    isCreatingOrder.value = false
  }
}
</script>

<template>
  <div @click="cartAction" class="fixed top-0 left-0 h-full w-full bg-black z-10 opacity-70"></div>
  <div class="flex flex-col bg-white w-96 h-full fixed right-0 top-0 z-20 p-8">
    <drawer-head />

    <info-block
      v-if="orderId"
      title="Заказ оформлен!"
      :descr="`Ваш заказ №${orderId} скоро будет передан курьерской доставке.`"
      img="/order-success-icon.png"
    />

    <info-block
      v-else-if="!totalPrice"
      title="Корзина пуста"
      descr="Добавте хотя бы пару кроссовок, чтобы сделать заказ."
      img="/package-icon.png"
    />

    <div v-else class="flex flex-col h-full">
      <cart-item-list />

      <div class="flex flex-col gap-4 mt-7">
        <div class="flex gap-2">
          <span>Итого:</span>
          <div class="flex-1 border-b border-dashed"></div>
          <b>{{ totalPrice }} $</b>
        </div>
        <div class="flex gap-2">
          <span>Налог 5%:</span>
          <div class="flex-1 border-b border-dashed"></div>
          <b>{{ taxPrice }} $</b>
        </div>
        <button
          :disabled="disableBtn"
          @click="createOrder"
          class="mt-4 transition bg-lime-500 w-full rounded-xl py-3 text-white disabled:bg-slate-300 hover:bg-lime-600 active:bg-lime-700 cursor-pointer"
        >
          {{ isCreatingOrder ? 'Заказ принят' : 'Оформить заказ' }}
        </button>
      </div>
    </div>
  </div>
</template>
