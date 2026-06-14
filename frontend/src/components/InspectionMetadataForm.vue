<script setup>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
import CreateScopeDialog from '@/components/CreateScopeDialog.vue'

const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
})

const store = useStore()
const formRef = ref(null)
const scopeDialog = ref(false)

const serviceTypes = computed(() => store.state.master.serviceTypes)
const scopes = computed(() => store.state.master.scopesOfWork)
const inspectionTypes = computed(() => store.state.master.inspectionTypes)
const customers = computed(() => store.state.master.customers)
const locations = computed(() => store.state.master.locations)

const scopeIncluded = computed(() => {
  const scope = scopes.value.find((item) => item.id === props.form.scope_of_work_id)

  return scope?.parameters ?? []
})

const rules = {
  service_type: [{ required: true, message: 'Service type is required', trigger: 'change' }],
  scope_of_work_id: [{ required: true, message: 'Scope of work is required', trigger: 'change' }],
  customer_id: [{ required: true, message: 'Customer is required', trigger: 'change' }],
  location_id: [{ required: true, message: 'Location is required', trigger: 'change' }],
  estimated_completion_date: [
    { required: true, message: 'Estimated completion date is required', trigger: 'change' },
  ],
  related_to: [{ required: true, message: 'Related To is required', trigger: 'blur' }],
}

function onScopeCreated(scope) {
  props.form.scope_of_work_id = scope.id
}

function validate() {
  return formRef.value.validate()
}

defineExpose({ validate })
</script>

<template>
  <el-form ref="formRef" :model="form" :rules="rules" label-position="top">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
      <el-form-item label="Service Type" prop="service_type">
        <el-select v-model="form.service_type" placeholder="Select service type" class="w-full">
          <el-option v-for="t in serviceTypes" :key="t.value" :label="t.label" :value="t.value" />
        </el-select>
      </el-form-item>

      <el-form-item label="Scope of Work" prop="scope_of_work_id">
        <div class="flex gap-2 w-full">
          <el-select
            v-model="form.scope_of_work_id"
            placeholder="Select scope of work"
            filterable
            class="flex-1"
          >
            <el-option v-for="s in scopes" :key="s.id" :label="s.name" :value="s.id" />
          </el-select>
          <el-button @click="scopeDialog = true">+ New SOW</el-button>
        </div>
      </el-form-item>
    </div>

    <el-form-item v-if="scopeIncluded.length" label="Scope Included">
      <div class="flex flex-wrap gap-2">
        <el-tag v-for="p in scopeIncluded" :key="p.id" type="info">{{ p.name }}</el-tag>
      </div>
    </el-form-item>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
      <el-form-item label="Inspection Type" prop="inspection_type_id">
        <el-select
          v-model="form.inspection_type_id"
          placeholder="Select type"
          clearable
          class="w-full"
        >
          <el-option v-for="t in inspectionTypes" :key="t.id" :label="t.name" :value="t.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Customer" prop="customer_id">
        <el-select v-model="form.customer_id" placeholder="Select customer" filterable class="w-full">
          <el-option v-for="c in customers" :key="c.id" :label="c.name" :value="c.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Location" prop="location_id">
        <el-select v-model="form.location_id" placeholder="Select location" filterable class="w-full">
          <el-option v-for="l in locations" :key="l.id" :label="l.name" :value="l.id" />
        </el-select>
      </el-form-item>

      <el-form-item label="Estimated Completion Date" prop="estimated_completion_date">
        <el-date-picker
          v-model="form.estimated_completion_date"
          type="date"
          value-format="YYYY-MM-DD"
          placeholder="Pick a date"
          class="w-full"
        />
      </el-form-item>

      <el-form-item label="Related To" prop="related_to">
        <el-input v-model="form.related_to" placeholder="e.g. CO-02023-001" />
      </el-form-item>

      <el-form-item label="DVC Code" prop="dvc_code">
        <el-input v-model="form.dvc_code" placeholder="DVC code" />
      </el-form-item>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
      <el-form-item label="Charge to Customer">
        <el-switch v-model="form.charge_to_customer" />
      </el-form-item>

      <el-form-item label="Status">
        <el-tag type="info">Open</el-tag>
      </el-form-item>
    </div>

    <el-form-item label="Note to Yard" prop="note_to_yard">
      <el-input v-model="form.note_to_yard" type="textarea" :rows="2" placeholder="Notes to yard" />
    </el-form-item>

    <CreateScopeDialog v-model="scopeDialog" @created="onScopeCreated" />
  </el-form>
</template>
