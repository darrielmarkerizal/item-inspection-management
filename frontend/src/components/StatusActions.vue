<script setup>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
import { ElMessage } from 'element-plus'

const props = defineProps({
  inspection: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const store = useStore()
const submitting = ref(false)

const status = computed(() => props.inspection.status?.value)

const nextAction = computed(() => {
  if (status.value === 'open') {
    return {
      label: 'Submit for Review',
      target: 'for_review',
      confirm: 'Submit this inspection for review? Its content will be locked.',
    }
  }
  if (status.value === 'for_review') {
    return {
      label: 'Complete',
      target: 'completed',
      confirm: 'Mark this inspection as completed? It will be locked permanently.',
    }
  }

  return null
})

async function transition(target) {
  submitting.value = true
  try {
    await store.dispatch('inspections/updateStatus', { id: props.inspection.id, status: target })
    ElMessage.success('Status updated')
    emit('updated')
  } catch (error) {
    ElMessage.error(error.message ?? 'Failed to update status')
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="flex items-center gap-2">
    <el-popconfirm
      v-if="nextAction"
      :title="nextAction.confirm"
      width="260"
      @confirm="transition(nextAction.target)"
    >
      <template #reference>
        <el-button type="primary" :loading="submitting">{{ nextAction.label }}</el-button>
      </template>
    </el-popconfirm>
    <el-tag v-else type="success" effect="plain">Completed — locked</el-tag>
  </div>
</template>
