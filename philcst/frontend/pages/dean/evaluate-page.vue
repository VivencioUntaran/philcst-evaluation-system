<template>
  <div>
    <div v-if="loaded">
      <div class="evaluate-page__select-container">
        <label for="">Select Evaluation Type</label>
        <select v-model="selectedType">
          <option value="" disabled selected> Select Evaluation Type </option>
          <option 
            v-for="(item, index) in selections"
            :key="index"
            :value="item.value"
          >
            {{ item.label }}
          </option>
        </select>
      </div>
      <EvaluationComponent 
        :questionnaireType="'dean-to-faculty'"
        v-if="selectedType === 'dean-to-faculty'"
      />
      <EvaluationComponent 
        :questionnaireType="'peer-to-peer'"
        v-if="selectedType === 'peer-to-peer'"
      />
    </div>
    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>
  
<script setup>
const api = useApi()
const loaded = ref(false)

const selectedType = ref('')
const selections = [
  {
    label: 'Dean to Faculty',
    value: 'dean-to-faculty',
  },
  {
    label: 'Peer to Peer',
    value: 'peer-to-peer',
  },
]

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