<template>
  <div class="criteria-component">
    <div class="criteria-component__header stack-container mb-3">
      <h2> {{ props.criteria.name }} </h2>
    </div>
    <div class="criteria-component__points d-flex justify-content-end">
      <span class="fw-medium"> Points: </span>
      
      <div class="criteria-component__point-wrapper d-flex">
        <div
            v-for="(point, index) in props.criteria.criteria_points"
            :key="index"
            class="criteria-component__point-item mx-2"
          >
            <span class="point"> {{ point.label }} </span>
        </div>
      </div>
    </div>
    <div class="criteria-component__question-container">
      <EvaluationQuestionItem 
        v-for="(question, index) in criteria.questions"
        :key="index"
        :question="question"
        :entryId="entryId"
        :criteriaId="props.criteria.id"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  criteria: {
    type: Object,
    default: () => {}
  },
  entryId: {
    type: Number,
    default: 0,
  },
})
</script>

<style lang="scss" scoped>
// .criteria-component {
//   &__header {
//     adkasdksajdakd
//   }
// }

.point {
  display: block;
  background-color: #D9D9D9;
  padding: 3px 12px;
  min-width: 60px;
  width: 60px;
  border-radius: 2px;
  color: black;
  text-align: center;
}

.stack-container {
    background-color: #954087;
    border-radius: .5rem;
    margin: 8px 0px;
    padding: 1rem;
    max-width: 100%;
    color: white;
    letter-spacing: 2px;
}
</style>