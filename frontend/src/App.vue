<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { RouterLink, RouterView } from 'vue-router'

const store = useStore()

const loaded = computed(() => store.state.master.loaded)
const error = computed(() => store.state.master.error)

function bootstrap() {
  store.dispatch('master/prefetch')
}

onMounted(bootstrap)
</script>

<template>
  <el-container class="min-h-screen">
    <el-header class="flex items-center border-b border-gray-200 bg-white">
      <RouterLink to="/" class="text-lg font-semibold text-gray-800 no-underline">
        Inspection Management
      </RouterLink>
    </el-header>
    <el-main class="bg-gray-50">
      <el-result
        v-if="error && !loaded"
        icon="error"
        title="Failed to load application data"
        :sub-title="error"
      >
        <template #extra>
          <el-button type="primary" @click="bootstrap">Retry</el-button>
        </template>
      </el-result>

      <el-skeleton v-else-if="!loaded" :rows="6" animated />

      <RouterView v-else />
    </el-main>
  </el-container>
</template>
