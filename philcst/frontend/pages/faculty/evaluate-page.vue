<template>
  <div>
    <div v-if="loaded">
      <EvaluationComponent :questionnaireType="'peer-to-peer'"/>
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
  background-color: #D9D9D9;
  margin: 5px 1rem -2px 1rem;
  padding: 0.5rem;
  max-width: 98%;
}

button {
  display: block;
}
</style>