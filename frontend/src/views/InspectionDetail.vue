<script setup>
defineProps({
  id: {
    type: [String, Number],
    required: true,
  },
})
</script>

<template>
  <div>
    <h2 class="text-xl font-semibold mb-4">Inspection #{{ id }}</h2>
    <el-empty description="Detail page will be implemented in #15" />
  </div>
</template>
