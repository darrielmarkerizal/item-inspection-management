<script setup>
import { computed, watch } from 'vue'
import { useStore } from 'vuex'
import LotSelector from '@/components/LotSelector.vue'

const props = defineProps({
  item: { type: Object, required: true },
})

defineEmits(['remove'])

const store = useStore()
const items = computed(() => store.state.master.items)

const itemLots = computed(() => {
  const found = items.value.find((i) => i.id === props.item.item_id)

  return found?.lots ?? []
})

function newLot() {
  return {
    item_lot_id: null,
    lot_no: null,
    allocation: null,
    owner: null,
    condition: null,
    available_qty: null,
    qty_required: 0,
    inspection_required: true,
  }
}

function addLot() {
  props.item.lots.push(newLot())
}

function removeLot(index) {
  props.item.lots.splice(index, 1)
}

watch(
  () => props.item.item_id,
  () => {
    props.item.lots = [newLot()]
  },
)
</script>

<template>
  <el-card shadow="never" class="mb-3 border border-gray-200">
    <div class="flex items-center gap-3 mb-3">
      <el-select v-model="item.item_id" placeholder="Select an item" filterable class="flex-1">
        <el-option
          v-for="i in items"
          :key="i.id"
          :label="`${i.code} — ${i.description}`"
          :value="i.id"
        />
      </el-select>
      <el-button text type="danger" @click="$emit('remove')">Remove Item</el-button>
    </div>

    <template v-if="item.item_id">
      <div class="overflow-x-auto">
        <div class="min-w-[760px]">
          <div class="grid grid-cols-12 gap-2 text-xs text-gray-500 font-medium mb-1">
            <div class="col-span-2">Lot</div>
            <div class="col-span-2">Allocation</div>
            <div class="col-span-2">Owner</div>
            <div class="col-span-2">Condition</div>
            <div class="col-span-1">Avail</div>
            <div class="col-span-1">Qty Req</div>
            <div class="col-span-1">Insp</div>
            <div class="col-span-1"></div>
          </div>

          <LotSelector
            v-for="(lot, index) in item.lots"
            :key="index"
            :lot="lot"
            :item-lots="itemLots"
            class="mb-2"
            @remove="removeLot(index)"
          />
        </div>
      </div>

      <el-button size="small" class="mt-2" @click="addLot">+ Add Lot</el-button>
    </template>
  </el-card>
</template>
