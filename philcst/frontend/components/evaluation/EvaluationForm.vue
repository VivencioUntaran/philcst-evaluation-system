<template>
  <div class="evaluation-form">
    <div 
      class="evaluation-form__body"
      v-if="evaluationFormData.loaded"
    >
      <div class="evaluation-form__evaluatee stack-container">
        <div class="letter-spacing">
          <b>{{ evaluationData.evaluatee.role.role.toUpperCase() }}: </b> <span> {{ evaluationData.evaluatee.name }} </span>
        </div>
        <div class="letter-spacing">
          <b>Department: </b> <span> {{ evaluationData.evaluatee.department.department }} </span>
        </div>
        <div class="letter-spacing">
          <b>Academic Year: </b> <span> {{ evaluationFormData.academicYear.year }} {{ evaluationFormData.academicYear.semester }} semester </span>
        </div>
      </div>
      <div class="evaluation-form__caption m-4">
        <div v-html="evaluationFormData.caption" class="letter-spacing"></div>
      </div>

      <div v-if="evaluationFormData?.evaluationEntry?.questionnaire?.criterias?.length">
        <div class="evaluation-form__criteria-wrapper">
            <EvaluationCriteriaComponent
              v-for="(criteria, index) in evaluationFormData.evaluationEntry.questionnaire.criterias"
              :key="index"
              :criteria="criteria"
              :entryId="evaluationFormData.evaluationEntry.id"
            />
        </div>
        <div class="evaluation-form__comment-wrapper">
          <div class="d-flex flex-column fw-medium">
            <label for="comment" class="m-2"> Comment (Optional) </label>
            <textarea
              name="comment"
              cols="30"
              rows="10"
              v-model="evaluationFormData.comments"
              @input="inputTimeout"
            >
            </textarea>
          </div>
          <div 
            class="d-flex flex-column"
            v-if="props.questionnaireType === 'peer-to-peer' && evaluationFormData.comments"
          >
            <label for="comment"> Points {{ evaluationFormData.requiredPoint ? '*' : '(optional)' }} </label>
            <select 
              class="evaluation-form__point-select"
              v-model="evaluationFormData.evaluationPoints"
              @change="updateComment"
            >
              <option value="" selected> No point </option>
              <option 
                v-for="(item, index) in evaluationFormData.pointsSelection"
                :key="index"
                :value="item"
              >
                {{ item }}
              </option>
            </select>
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <button 
            type="button"
            :class="['evaluation-form__submit', !evaluationFormData.enabledSubmit && 'evaluation-form__submit--disabled']"
            @click="handleSubmit"
          >
            {{ evaluationFormData.enabledSubmit ? 'Submit' : 'Please wait...' }}
          </button>
        </div>
      </div>
      <div v-else>
        <p> No criteria questions. </p>
      </div>
    </div>
    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script setup>
const api = useApi()

const emit = defineEmits(['backToEvaluation'])

const props = defineProps({
  evaluationData: {
    type: Object,
    default: () => {},
  },
  questionnaireType: {
    type: String,
    default: '',
  },
})

const evaluationFormData = reactive({
  loaded: false,
  entryPayload: {},
  evaluationEntry: {},
  academicYear: {},
  caption: `<p><b>Directions:</b> Check the corresponding points earned by the faculty member for each criteria.</p>`,
  questionnaire: {},
  comments: '',
  enabledSubmit: true,
  pointsSelection: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
  evaluationPoints: '',
  requiredPoint: false,
})

onMounted(async () => {
  await initialize()
  evaluationFormData.loaded = true
})

async function initialize () {
  await dataBuilder()
  await payloadBuilder()
  await entryChecker()
  await checkRequiredPoint()
}


async function dataBuilder () {
  // set academic year
  evaluationFormData.academicYear = props.evaluationData.activeYear
  const questionnaire = props.evaluationData.questionnaires.find(cq => cq.slug === props.questionnaireType)
  if (questionnaire) {
    evaluationFormData.questionnaire = questionnaire
  }
}

async function payloadBuilder () {
  const academicYearId = evaluationFormData.academicYear.id
  const questionnaireId = evaluationFormData.questionnaire.id // dynamic- to be filled soon
  const entryPayload = reactive({
    evaluator_id: props.evaluationData.evaluator.id,
    evaluatee_id: props.evaluationData.evaluatee.id,
    academic_year_id: academicYearId,
    questionnaire_id: questionnaireId,
  })
  evaluationFormData.entryPayload = entryPayload
}

async function checkRequiredPoint () {
  if (props.questionnaireType === 'peer-to-peer') {
    evaluationFormData.requiredPoint = true
  }
}

async function entryChecker () {
  const response = await api({
      url: '/evaluation-entries/entry-checker',
      method: 'POST',
      headers: {
          "Content-Type": "application/json",
      },
      data: evaluationFormData.entryPayload
  })
  
  const entry = response.data.record
  if (!entry) {
    await createEntryData()
  } else {
    if (entry.isDone) {
      alert('You\'re done evaluating this faculty. Select another faculty.')
      handleBack()
      return
    }
    await fetchEntryData(entry)
  }
}

async function createEntryData () {
  const response = await api({
      url: '/evaluation-entries/create',
      method: 'POST',
      headers: {
          "Content-Type": "application/json",
      },
      data: evaluationFormData.entryPayload
  })
  await fetchEntryData(response.data.record)
}

async function fetchEntryData (entry) {
  const entryPayload = {
    entry_id: entry.id,
    questionnaire: props.questionnaireType
  }
  const response = await api({
      url: 'evaluation-entries/single-entry',
      method: 'POST',
      headers: {
          "Content-Type": "application/json",
      },
      data: entryPayload
  })
  evaluationFormData.evaluationEntry = response.data.record
  evaluationFormData.academicYear = response.data.academic_year
  evaluationFormData.comments = response.data.record.comments
  return response.data.record
}

async function updateComment () {
  const entryPayload = {
    entry_id: evaluationFormData.evaluationEntry.id,
    comments: evaluationFormData.comments,
    points: evaluationFormData.evaluationPoints ? evaluationFormData.evaluationPoints : 0,
  }
  const response = await api({
      url: '/evaluation/update-coment',
      method: 'POST',
      headers: {
          "Content-Type": "application/json",
      },
      data: entryPayload
  })
  evaluationFormData.enabledSubmit = true
}

const typingTimeOut = ref(0)
function inputTimeout () {
  evaluationFormData.enabledSubmit = false
  clearTimeout(typingTimeOut.value)
  typingTimeOut.value = setTimeout(() => {
    updateComment()
  }, 2000)
}

// submit logic
async function handleSubmit () {
  const valid = await handleValidation()
  if (!valid) {
    alert('Please answer all questions highlighted in red.')
    return false
  }
  const pointValid = await handlePointValidation()
  if (!pointValid) {
    alert('Please select a point.')
    return false
  }
  await handleUpateStatus()
}

async function handleUpateStatus () {
  const entryPayload = {
    entry_id: evaluationFormData.evaluationEntry.id
  }
  const response = await api({
      url: '/evaluation/update-status',
      method: 'POST',
      headers: {
          "Content-Type": "application/json",
      },
      data: entryPayload
  })
  alert('You have successfully evaluated this faculty. You will now be redirected to evaluation page.')
  handleBack()
}

async function handleValidation () {
  const entry = evaluationFormData.evaluationEntry
  await fetchEntryData(entry)
  
  const unAnsweredElements = document.querySelectorAll('.M7WEby4uyF')
  if (unAnsweredElements?.length) {
    unAnsweredElements.forEach(el => {
      el.classList.add('question-item--error')
    })
    return false
  } else {
    return true
  }
}

async function handlePointValidation () {
  if (evaluationFormData.requiredPoint && evaluationFormData.comments) {
    if (evaluationFormData.evaluationPoints) {
      return true
    } else {
      return false
    }
  } else {
    return true
  }
}

function handleBack () {
  emit('backToEvaluation')
}
</script>

<style lang="scss" scoped>
.evaluation-form {
  &__submit {
    &--disabled {
      opacity: 0.8;
      pointer-events: none;
    }
  }
}

.stack-container {
    background-color: #954087;
    border-radius: .5rem;
    margin: 8px 0px;
    padding: 1rem;
    max-width: 100%;
    color: white;
    letter-spacing: 1px;
}

.letter-spacing {
  letter-spacing: 1px;
}

button {
  border: none;
  border-radius: 2px;
  padding: 5px 16px;
  margin-top: 8px;
  background-color: #954087;
  color: white;
  letter-spacing: 2px;
  
}

</style>
