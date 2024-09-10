<script setup>
import CardList from '@/components/CardList.vue'
import { inject, onMounted, reactive, ref, watch } from 'vue'
import debounce from 'lodash.debounce'
import axios from 'axios'

const apiServer = inject('apiServer')

const { cartItems } = inject('cartProps')

const items = ref([])

const filters = reactive({
  sortBy: '',
  search: ''
})

const onChangeSelect = (event) => {
  filters.sortBy = event.target.value
}

const onChangeSearchInput = debounce((event) => {
  filters.search = event.target.value
}, 500)

const fetchItems = async () => {
  try {
    const params = {}

    if (filters.sortBy) params.sortBy = filters.sortBy
    if (filters.search) params.search = filters.search

    const { sneakers, likes, cart } = (await axios.get(apiServer, { params })).data

    items.value = sneakers.map((item) => ({
      ...item,
      isLiked: likes && !!likes.find((like) => like.id === item.id),
      isAdded: cart && !!cart.find((added) => added.id === item.id)
    }))

    cartItems.value = items.value.filter((item) => item.isAdded)
  } catch (err) {
    console.log(err)
  }
}

onMounted(fetchItems)

watch(cartItems, () => {
  if (cartItems.value.length === 0) {
    items.value = items.value.map((item) => ({
      ...item,
      isAdded: false
    }))
  }
})

watch(filters, fetchItems)
</script>

<template>
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-bold">Все кроссовки</h2>

    <div class="flex gap-4">
      <select @change="onChangeSelect" class="py-2 px-3 border rounded-md outline-none">
        <option value="">По умолчанию</option>
        <option value="name">По названию</option>
        <option value="asc">По цене (дешевые)</option>
        <option value="desc">По цене (дорогие)</option>
      </select>

      <div class="relative">
        <img class="absolute top-3 left-4" src="/search.svg" alt="search" />
        <input
          @input="onChangeSearchInput"
          type="text"
          class="border rounded-md py-2 pl-11 pr-4 outline-none focus:border-gray-400 transition"
          placeholder="Search"
        />
      </div>
    </div>
  </div>

  <div class="mt-10">
    <CardList :items="items" />
  </div>
</template>
