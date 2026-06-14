<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { ElMessage } from 'element-plus'
import InspectionMetadataForm from '@/components/InspectionMetadataForm.vue'

const router = useRouter()
const metadataRef = ref(null)

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

async function onSubmit() {
  try {
    await metadataRef.value.validate()
  } catch {
    return
  }

  ElMessage.info('Metadata valid. Order items and submit arrive in #13/#14.')
}
</script>

<template>
  <div class="max-w-4xl">
    <h2 class="text-xl font-semibold mb-4">Create Inspection</h2>

    <el-card>
      <InspectionMetadataForm ref="metadataRef" :form="form" />
    </el-card>

    <div class="flex justify-end gap-2 mt-4">
      <el-button @click="router.push({ name: 'inspections.index' })">Cancel</el-button>
      <el-button type="primary" @click="onSubmit">Submit</el-button>
    </div>
  </div>
</template>
