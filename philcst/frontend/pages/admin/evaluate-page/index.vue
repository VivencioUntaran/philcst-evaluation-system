<template>
  <div>
    <div v-if="loaded">
      <h1> Evaluate </h1>
      <EvaluationComponent :questionnaireType="'admin-to-faculty'"/>
    </div>
    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>
  
<script setup>
const api = useApi()
const loaded = ref(false)

onMounted(async () => {
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



</style>