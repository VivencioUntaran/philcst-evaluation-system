<template>
  <div class="report-page">
    <div v-if="loaded">
      <div class="report-page__header d-flex justify-content-between w-100">
        <h1 class="report-page__title"> Report </h1>
      </div>
      <ReportComponent
        :user="user"
      />
    </div>
    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>
  
<script setup>
const api = useApi()
const { getCurrentUser } = useCustomAuth()
const loaded = ref(false)
const user = ref({})

onMounted(async () => {
  const currentUser = await getCurrentUser()
  user.value = currentUser
  loaded.value = true
})

</script> 
  
<style lang="scss" scoped>
.entityContainer {
  background-color: #dd4848;
  margin: 5px 1rem -2px 1rem;
  padding: 0.5rem;
  max-width: 98%;
}

// button {
//   display: block;
// }
.report-page {
  &__header {
    padding: 0 40px 0 0px;
  }
  &__title {
    &--hidden {
      display: none;
    }
  }
}



</style>