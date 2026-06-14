<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import StatusTag from '@/components/StatusTag.vue'
import StatusActions from '@/components/StatusActions.vue'

const props = defineProps({
  id: { type: [String, Number], required: true },
})

const router = useRouter()
const store = useStore()
const error = ref(null)

const inspection = computed(() => store.state.inspections.current)
const loading = computed(() => store.state.inspections.loading)
const ready = computed(() => inspection.value && String(inspection.value.id) === String(props.id))

async function load() {
  error.value = null
  try {
    await store.dispatch('inspections/fetchOne', {
      id: props.id,
      params: { include: 'statusHistories' },
    })
  } catch (e) {
    error.value = e
  }
}

function goToList() {
  router.push({ name: 'inspections.index' })
}

onMounted(load)
</script>

<template>
  <div class="max-w-5xl">
    <el-button text class="mb-3" @click="goToList">&larr; Back to inspections</el-button>

    <el-skeleton v-if="loading && !ready" :rows="8" animated />

    <el-result
      v-else-if="error"
      :icon="error.status === 404 ? 'info' : 'error'"
      :title="error.status === 404 ? 'Inspection not found' : 'Failed to load inspection'"
      :sub-title="error.message"
    >
      <template #extra>
        <el-button type="primary" @click="goToList">Back to list</el-button>
      </template>
    </el-result>

    <template v-else-if="ready">
      <el-card class="mb-4">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center gap-3">
              <h2 class="text-xl font-semibold">{{ inspection.request_no }}</h2>
              <StatusTag :status="inspection.status" />
            </div>
            <p class="text-gray-500 mt-1">{{ inspection.service_type?.label }}</p>
          </div>
          <StatusActions :inspection="inspection" @updated="load" />
        </div>

        <el-divider />

        <el-descriptions :column="3" border>
          <el-descriptions-item label="Inspection Type">
            {{ inspection.inspection_type?.name ?? '-' }}
          </el-descriptions-item>
          <el-descriptions-item label="Location">
            {{ inspection.location?.name ?? '-' }}
          </el-descriptions-item>
          <el-descriptions-item label="Customer">
            {{ inspection.customer?.name ?? '-' }}
          </el-descriptions-item>
          <el-descriptions-item label="Related To">{{ inspection.related_to ?? '-' }}</el-descriptions-item>
          <el-descriptions-item label="DVC Code">{{ inspection.dvc_code ?? '-' }}</el-descriptions-item>
          <el-descriptions-item label="Charge to Customer">
            {{ inspection.charge_to_customer ? 'Yes' : 'No' }}
          </el-descriptions-item>
          <el-descriptions-item label="Date Submitted">
            {{ inspection.date_submitted ?? '-' }}
          </el-descriptions-item>
          <el-descriptions-item label="Estimated Completion">
            {{ inspection.estimated_completion_date ?? '-' }}
          </el-descriptions-item>
          <el-descriptions-item label="Note to Yard">
            {{ inspection.note_to_yard ?? '-' }}
          </el-descriptions-item>
        </el-descriptions>
      </el-card>

      <el-card v-if="inspection.scope_of_work" class="mb-4">
        <template #header><span class="font-semibold">Scope of Work</span></template>
        <p class="font-medium">{{ inspection.scope_of_work.name }}</p>
        <p class="text-gray-500 text-sm mb-3">{{ inspection.scope_of_work.description }}</p>
        <div class="flex flex-wrap gap-2">
          <el-tag v-for="p in inspection.scope_of_work.parameters" :key="p.id" type="info">
            {{ p.name }}
          </el-tag>
        </div>
      </el-card>

      <el-card class="mb-4">
        <template #header><span class="font-semibold">Item Information</span></template>
        <div v-for="item in inspection.items" :key="item.id" class="mb-4">
          <p class="font-medium">{{ item.item?.code }} &mdash; {{ item.item_description }}</p>
          <el-table :data="item.lots" size="small" class="mt-2">
            <el-table-column prop="lot_no" label="Lot No" />
            <el-table-column prop="allocation" label="Allocation" />
            <el-table-column prop="owner" label="Owner" />
            <el-table-column prop="condition" label="Condition" />
            <el-table-column prop="qty_required" label="Qty Required" align="right" />
            <el-table-column label="Inspection Required" align="center" width="160">
              <template #default="{ row }">
                <el-tag :type="row.inspection_required ? 'success' : 'info'" size="small">
                  {{ row.inspection_required ? 'Yes' : 'No' }}
                </el-tag>
              </template>
            </el-table-column>
          </el-table>
        </div>
        <el-empty v-if="!inspection.items.length" description="No items" :image-size="60" />
      </el-card>

      <el-card class="mb-4">
        <template #header><span class="font-semibold">Charges to Customer</span></template>
        <el-table v-if="inspection.charges.length" :data="inspection.charges" size="small">
          <el-table-column prop="order_no" label="Order No" />
          <el-table-column prop="service_description" label="Service Description" />
          <el-table-column prop="qty" label="Qty" align="right" />
          <el-table-column prop="unit_price" label="Unit Price" align="right" />
          <el-table-column prop="total" label="Total" align="right" />
        </el-table>
        <el-empty v-else description="No charges" :image-size="60" />
      </el-card>

      <el-card v-if="inspection.status_histories?.length">
        <template #header><span class="font-semibold">Status History</span></template>
        <el-timeline>
          <el-timeline-item
            v-for="h in inspection.status_histories"
            :key="h.id"
            :timestamp="h.changed_at"
          >
            <span v-if="h.from_status">{{ h.from_status.label }} &rarr; </span>{{ h.to_status.label }}
          </el-timeline-item>
        </el-timeline>
      </el-card>
    </template>
  </div>
</template>
