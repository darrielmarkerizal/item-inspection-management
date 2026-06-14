import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', redirect: '/inspections' },
  {
    path: '/inspections',
    name: 'inspections.index',
    component: () => import('@/views/InspectionList.vue'),
  },
  {
    path: '/inspections/create',
    name: 'inspections.create',
    component: () => import('@/views/InspectionCreate.vue'),
  },
  {
    path: '/inspections/:id',
    name: 'inspections.show',
    component: () => import('@/views/InspectionDetail.vue'),
    props: true,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/NotFound.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
