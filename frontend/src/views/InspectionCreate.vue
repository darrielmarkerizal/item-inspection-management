<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import { ElMessage, ElNotification } from 'element-plus'
import InspectionMetadataForm from '@/components/InspectionMetadataForm.vue'
import OrderInformation from '@/components/OrderInformation.vue'
import { buildInspectionPayload, splitValidationErrors } from '@/utils/inspectionForm'

const router = useRouter()
const store = useStore()
const metadataRef = ref(null)
const submitting = ref(false)
const serverErrors = ref({})

const form = reactive({
  service_type: null,
  inspection_type_id: null,
  scope_of_work_id: null,
  location_id: null,
  customer_id: null,
  related_to: '',
  dvc_code: '',
  estimated_completion_date: null,
  charge_to_customer: false,
  note_to_yard: '',
  items: [],
})

function validateItems() {
  if (!form.items.length) {
    ElMessage.error('Add at least one item')

    return false
  }

  for (const item of form.items) {
    if (!item.item_id) {
      ElMessage.error('Select an item for each row')

      return false
    }
    if (!item.lots.length) {
      ElMessage.error('Add at least one lot per item')

      return false
    }
    for (const lot of item.lots) {
      if (!lot.item_lot_id) {
        ElMessage.error('Resolve every lot via Lot / Allocation / Owner / Condition')

        return false
      }
      if (lot.qty_required == null || lot.qty_required < 0) {
        ElMessage.error('Qty required must be zero or more')

        return false
      }
    }
  }

  return true
}

function handleError(error) {
  if (error.status === 422 && error.errors) {
    const { fields, nested } = splitValidationErrors(error.errors)
    serverErrors.value = fields

    if (nested.length) {
      ElNotification({ title: 'Validation failed', message: nested.join('\n'), type: 'error' })
    }
    if (Object.keys(fields).length) {
      ElMessage.error('Please fix the highlighted fields')
    }

    return
  }

  ElMessage.error(error.message ?? 'Failed to create inspection')
}

async function onSubmit() {
  serverErrors.value = {}

  try {
    await metadataRef.value.validate()
  } catch {
    return
  }

  if (!validateItems()) {
    return
  }

  submitting.value = true
  try {
    const inspection = await store.dispatch('inspections/create', buildInspectionPayload(form))
    ElMessage.success('Inspection created')
    router.push({ name: 'inspections.show', params: { id: inspection.id } })
  } catch (error) {
    handleError(error)
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="max-w-5xl">
    <h2 class="text-xl font-semibold mb-4">Create Inspection</h2>

    <el-card class="mb-4">
      <InspectionMetadataForm ref="metadataRef" :form="form" :server-errors="serverErrors" />
    </el-card>

    <el-card>
      <OrderInformation :items="form.items" />
    </el-card>

    <div class="flex justify-end gap-2 mt-4">
      <el-button @click="router.push({ name: 'inspections.index' })">Cancel</el-button>
      <el-button type="primary" :loading="submitting" @click="onSubmit">Submit</el-button>
    </div>
  </div>
</template>
