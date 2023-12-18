<template>
  <div>
    <SideBar v-if="currentUser"/>
    <div class="pageContent">
      <NuxtLayout>
        <NuxtPage/>
      </NuxtLayout>
    </div>
  </div>
</template>

<script setup>
  import { useRouter } from 'vue-router'
  const router = useRouter()
  const route = useRoute()

  const { checkIfAuthenticated, getCurrentUser, handleRedirection } = useCustomAuth()

  const noNeedAuthPages = ['/register', '/login']
  const fullPath = route.fullPath

  onMounted(() => {
    initialize()
  })

  const currentUser = computed(() => {
    return getCurrentUser()
  })

  function initialize () {
    if (!noNeedAuthPages.includes(fullPath)) {
      checkIfAuthenticated()
    }
  }  
</script>
