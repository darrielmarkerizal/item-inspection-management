<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <el-container class="min-h-screen">
    <el-header class="flex items-center border-b border-gray-200 bg-white">
      <RouterLink to="/" class="text-lg font-semibold text-gray-800 no-underline">
        Inspection Management
      </RouterLink>
    </el-header>
    <el-main class="bg-gray-50">
      <RouterView />
    </el-main>
  </el-container>
</template>
