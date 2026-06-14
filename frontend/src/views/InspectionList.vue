<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold">Inspections</h2>
      <el-button type="primary" @click="router.push({ name: 'inspections.create' })">
        Create Inspection
      </el-button>
    </div>
    <el-empty description="Listing will be implemented in #11" />
  </div>
</template>
