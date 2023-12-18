<template>
  <div 
    :class="['question-item', !questionItemData.answered && questionItemData.encryptedClass]"
    class="letter-spacing fw-medium"
  >
    <span> {{ question.question }} </span>
    <div class="question-item__selection-container d-flex justify-content-end">
      <div 
        v-for="(choice, index) in props.question.choices"
        :key="index"
        @click="selectChoice(choice)"
        class="question-item__option-wrapper mx-2"
      >
        <div
          :class="['question-item__option', `question-${question.id}`, `question-${question.id}-${choice.id}`,  checkIfChecked(choice)]"
        >
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const api = useApi()
const props = defineProps({
  question: {
    type: Object,
    default: () => {}
  },  
  entryId: {
    type: Number,
    default: 0,
  },
  criteriaId: {
    type: Number,
    default: 0,
  }
})

const questionItemData = reactive({
  activeClass: 'question-item__option--active',
  selectedAnswer: null,
  answered: false,
  encryptedClass: 'M7WEby4uyF',
})

async function selectChoice (choice) {
  const targetClass = `.question-${props.question.id}-${choice.id}`
  removeAllActive()
  addActive(targetClass)

  await saveChoice(choice)
}

async function saveChoice (choice) {
  if (questionItemData.selectedAnswer?.id === choice.id) {
    return false
  }
  questionItemData.selectedAnswer = choice
  questionItemData.answered = true

  const choicePayload = {
    evaluation_entry_id: props.entryId,
    criteria_id: props.criteriaId,
    question_id: props.question.id,
    choice_id: choice.id,
  }
  const response = await api({
      url: 'evaluation-answers/create',
      method: 'POST',
      headers: {
          "Content-Type": "application/json",
      },
      data: choicePayload
  })
}

function addActive (targetClass) {
  document.querySelector(targetClass).classList.add(questionItemData.activeClass)
}
function removeAllActive () {
  const questionClass = `.question-${props.question.id}`
  const choiceElements = document.querySelectorAll(questionClass)
  if (choiceElements.length) {
    choiceElements.forEach(choiceElement => {
      choiceElement.classList.remove(questionItemData.activeClass)
    })
  }
}
function checkIfChecked (choice) {
  return choice.evaluation_answer !== null ? questionItemData.activeClass : ''
}

function checkAnswered () {
  const hasAnswer = props.question.choices.find(cChoice => cChoice.evaluation_answer !== null)
  if (hasAnswer) {
    questionItemData.answered = true
  }
}

onMounted(() => {
  checkAnswered()
})

//check

</script>

<style lang="scss" scoped>
.question-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 15px 0px;
  border-radius: 5px;
  padding: 10px;
  padding-right: 0px;
  border: 1px solid rgb(128, 128, 128);
  &--error {
    border: 1px solid red;
  }
  &__option-wrapper {
    min-width: 60px;
    width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  &__option {
    width: 20px;
    min-width: 20px;
    height: 20px;
    border-radius: 100px;
    // border: 1px solid gray;
    // margin: 0 22px;
    cursor: pointer;
    transition: .2s ease-in-out;
    background-color: rgb(230, 230, 230);

    &--active {
      background-color: rgba(0, 0, 255, 0.486);
      pointer-events: none;
    }
  }
  &__selection-container {
    width: 420px;
    margin-left: 20px;
  }
}

.letter-spacing {
  letter-spacing: 1px;
}


</style>

