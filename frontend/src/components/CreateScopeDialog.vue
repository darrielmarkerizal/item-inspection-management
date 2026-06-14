<script setup>
import { computed, reactive, ref } from 'vue'
import { useStore } from 'vuex'
import { ElMessage } from 'element-plus'

const visible = defineModel({ type: Boolean })
const emit = defineEmits(['created'])
const store = useStore()

const formRef = ref(null)
const submitting = ref(false)
const form = reactive({
  name: '',
  service_type: null,
  description: '',
  parameter_ids: [],
})

const serviceTypes = computed(() => store.state.master.serviceTypes)
const parameters = computed(() => store.state.master.inspectionParameters)

const rules = {
  name: [{ required: true, message: 'Name is required', trigger: 'blur' }],
  service_type: [{ required: true, message: 'Service type is required', trigger: 'change' }],
}

function reset() {
  form.name = ''
  form.service_type = null
  form.description = ''
  form.parameter_ids = []
}

async function submit() {
  try {
    await formRef.value.validate()
  } catch {
    return
  }

  submitting.value = true
  try {
    const scope = await store.dispatch('master/createScope', { ...form })
    ElMessage.success('Scope of work created')
    emit('created', scope)
    reset()
    visible.value = false
  } catch (error) {
    ElMessage.error(error.message ?? 'Failed to create scope of work')
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <el-dialog v-model="visible" title="Create Scope of Work" width="500">
    <el-form
      ref="formRef"
      :model="form"
      :rules="rules"
      label-position="top"
      require-asterisk-position="right"
    >
      <el-form-item label="Name" prop="name">
        <el-input v-model="form.name" placeholder="Scope name" />
      </el-form-item>
      <el-form-item label="Service Type" prop="service_type">
        <el-select v-model="form.service_type" placeholder="Select service type" class="w-full">
          <el-option v-for="t in serviceTypes" :key="t.value" :label="t.label" :value="t.value" />
        </el-select>
      </el-form-item>
      <el-form-item label="Description">
        <el-input v-model="form.description" type="textarea" :rows="2" placeholder="Description" />
      </el-form-item>
      <el-form-item label="Scope Included (parameters)">
        <el-select v-model="form.parameter_ids" multiple placeholder="Select parameters" class="w-full">
          <el-option v-for="p in parameters" :key="p.id" :label="p.name" :value="p.id" />
        </el-select>
      </el-form-item>
    </el-form>
    <template #footer>
      <el-button @click="visible = false">Cancel</el-button>
      <el-button type="primary" :loading="submitting" @click="submit">Create</el-button>
    </template>
  </el-dialog>
</template>
