<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()
</script>

<template>
  <el-result icon="warning" title="404" sub-title="Page not found">
    <template #extra>
      <el-button type="primary" @click="router.push({ name: 'inspections.index' })">
        Back to Inspections
      </el-button>
    </template>
  </el-result>
</template>
