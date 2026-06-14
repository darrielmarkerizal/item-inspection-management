<script setup>
defineProps({
  status: {
    type: Object,
    required: true,
  },
})

const typeMap = {
  open: 'info',
  for_review: 'warning',
  completed: 'success',
}
</script>

<template>
  <el-tag :type="typeMap[status.value] ?? 'info'" disable-transitions>
    {{ status.label }}
  </el-tag>
</template>
