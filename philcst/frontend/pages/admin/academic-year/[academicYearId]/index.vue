<template>
</template>

<script setup>

onMounted(() => {
  const route = useRoute()
  const router = useRouter()

  router.push(`/admin/academic-year/${route.params.academicYearId}`)
})

</script>