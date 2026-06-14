<script setup>
import OrderItemRow from '@/components/OrderItemRow.vue'

const props = defineProps({
  items: { type: Array, required: true },
})

function addItem() {
  props.items.push({ item_id: null, lots: [] })
}

function removeItem(index) {
  props.items.splice(index, 1)
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold">Order Information</h3>
      <el-button type="primary" plain size="small" @click="addItem">+ Add Item</el-button>
    </div>

    <el-empty v-if="!items.length" description="No items added" :image-size="80" />

    <OrderItemRow
      v-for="(item, index) in items"
      :key="index"
      :item="item"
      @remove="removeItem(index)"
    />
  </div>
</template>
