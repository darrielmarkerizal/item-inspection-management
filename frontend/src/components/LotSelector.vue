<script setup>
import { computed, watch } from 'vue'

const props = defineProps({
  lot: { type: Object, required: true },
  itemLots: { type: Array, required: true },
})

defineEmits(['remove'])

const FIELDS = {
  lot_no: (l) => l.lot_no,
  allocation: (l) => l.allocation?.name,
  owner: (l) => l.owner?.name,
  condition: (l) => l.condition?.name,
}

function matches(l, except) {
  return Object.keys(FIELDS).every((field) => {
    if (field === except) {
      return true
    }
    const selected = props.lot[field]

    return !selected || FIELDS[field](l) === selected
  })
}

function optionsFor(field) {
  const values = props.itemLots.filter((l) => matches(l, field)).map((l) => FIELDS[field](l))

  return [...new Set(values)].filter((value) => value != null)
}

const lotOptions = computed(() => optionsFor('lot_no'))
const allocationOptions = computed(() => optionsFor('allocation'))
const ownerOptions = computed(() => optionsFor('owner'))
const conditionOptions = computed(() => optionsFor('condition'))

const resolved = computed(() => props.itemLots.filter((l) => matches(l, null)))

watch(
  resolved,
  (lots) => {
    if (lots.length === 1) {
      props.lot.item_lot_id = lots[0].id
      props.lot.available_qty = Number(lots[0].available_qty)
    } else {
      props.lot.item_lot_id = null
      props.lot.available_qty = null
    }
  },
  { immediate: true },
)
</script>

<template>
  <div class="grid grid-cols-12 gap-2 items-center">
    <el-select v-model="lot.lot_no" placeholder="Lot" clearable filterable class="col-span-2">
      <el-option v-for="o in lotOptions" :key="o" :label="o" :value="o" />
    </el-select>
    <el-select v-model="lot.allocation" placeholder="Allocation" clearable filterable class="col-span-2">
      <el-option v-for="o in allocationOptions" :key="o" :label="o" :value="o" />
    </el-select>
    <el-select v-model="lot.owner" placeholder="Owner" clearable filterable class="col-span-2">
      <el-option v-for="o in ownerOptions" :key="o" :label="o" :value="o" />
    </el-select>
    <el-select v-model="lot.condition" placeholder="Condition" clearable filterable class="col-span-2">
      <el-option v-for="o in conditionOptions" :key="o" :label="o" :value="o" />
    </el-select>
    <div class="col-span-1 text-sm text-gray-600">{{ lot.available_qty ?? '-' }}</div>
    <el-input-number
      v-model="lot.qty_required"
      :min="0"
      :max="lot.available_qty ?? undefined"
      size="small"
      controls-position="right"
      class="col-span-1"
    />
    <el-checkbox v-model="lot.inspection_required" class="col-span-1" />
    <el-button text type="danger" class="col-span-1" @click="$emit('remove')">Remove</el-button>
  </div>
</template>
