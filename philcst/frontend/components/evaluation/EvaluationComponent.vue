<template>
  <div class="evaluation-component bgCard">
    <div v-if="pageData.loaded">
      <div 
        class="evaluation-component__header"
        v-if="!pageData.isEvaluating"
      >
        <div 
          class="stack-container"
          v-if="showEvaluationForm"
        >
          <div class="d-flex justify-content-between">
            <DepartmentSelector
              v-model="pageData.departmentId"
              @valueChanged="handleSearch"
              :disabled="!isAdmin"
            />
          </div>
          <div v-if="isAdmin" class="d-flex justify-content-between">
            <div class="w-100 px-2">
              <label for=""> Select role </label>
              <select
                name="role"
                v-model="pageData.selectedRole"
                @change="handleSearch"
                class="w-100 mx-0"
              >
                <option value="" selected> All Roles </option>
                <option
                  v-for="(role, index) in pageData.roles"
                  :key="index"
                  :value="role.value"
                >
                  {{ role.label }}
                </option>
              </select>
            </div>
            <div class="w-100 px-2">
              <label>Search</label>
              <div class="d-flex align-items-center justify-content-between">
                <input
                type="text"
                name="seachQuery"
                v-model="pageData.searchQuery"
                :placeholder="isAdmin ? 'Search instructor or dean' : 'Search instructor'"
                class="mx-0 w-100"
                >

                <button
                  @click="handleSearch"
                  class="cstm-button mx-2 px-4"
                  >
                    Search
                </button>
              </div>
            </div>
          </div>
        </div>

        <!--- container to display already evaluated -->
        <div v-if="props.questionnaireType === 'peer-to-peer' && pageData.evaluatedFaculties.length">
          <p> You've already evaluated a peer. </p>
        </div>
      </div>
      
      <div 
        class="evaluation-component__body"
        v-if="!pageData.isEvaluating"
      >
        <!--- container for evaluation -->
        <div 
          class="table-container"
          v-if="showEvaluationForm"
        >
            <table 
              class="table table-bordered table-striped position-relative" 
              v-if="pageData.evaluatees.length"
            >
              <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name </th>
                    <th scope="col">Department</th>
                    <th scope="col">Role</th>
                    <th scope="col"> Status </th>
                    <th scope="col">Action</th>
                  </tr>
              </thead>
              <tbody>
                  <tr
                    v-for="(evaluatee, index) in pageData.evaluatees"
                    :key="index"
                  >
                    <th scope="row"> {{ index + 1 }} </th>
                    <td> {{ evaluatee.name }} </td>
                    <td> {{ evaluatee.department?.department }} </td>
                    <td> {{ evaluatee.role.role.toUpperCase() }} </td>
                    <td> {{ formattedEvaluatee(evaluatee).evaluated ? 'Evaluated' : 'Not yet evaluated' }} </td>
                    <td>

                        <button
                          :class="['cstm-button m-1', 'tdButton', formattedEvaluatee(evaluatee).evaluated && 'button-evaluated no-click']"
                          @click="beginEvaluation(evaluatee)"
                        > {{ formattedEvaluatee(evaluatee).evaluated ? 'Evaluated' : 'Evaluate' }} </button>
                    </td>
                  </tr>
              </tbody>
            </table>
            <div v-else>
              <p> No record found. </p>
            </div>
        </div>
      </div>

      <!--- Evaluation Form Container -->
      <EvaluationForm 
        :evaluationData="evaluationData"
        :questionnaireType="questionnaireType"
        v-if="pageData.isEvaluating"
        @backToEvaluation="handleBackToEvaluation"
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
  let currentUser = reactive({})
  
  const props = defineProps({
    questionnaireType: {
      type: String,
      default: '',
    },
  })

// logic for displaying table
  const pageData = reactive({
    evaluatees: [],
    loaded: false,
    departmentId: '',
    roles: [
      { label: 'Faculty', value: 'faculty' },
      { label: 'Dean', value: 'dean' },
    ],
    selectedRole: '',
    isEvaluating: false,
    searchQuery: '',
    evaluatedFaculties: [],
    peerEvaluatedFaculties: [],
  })

  const isAdmin = computed(() => {
    return currentUser && currentUser.role.role === 'admin' ? true : false
  })

  async function fetchUser () {
    const fetchedUser = getCurrentUser()
    currentUser = fetchedUser
  }

  async function fetchFaculties () {
    const user = currentUser
    console.log(pageData)
    const payload = {
      role: user.role.role,
      department_id: pageData.departmentId,
      evaluatee_name: pageData.searchQuery,
      evaluatee_role: pageData.selectedRole,
      evaluator_id: user.id,
      questionnaire_type: props.questionnaireType,
    }

    const response = await api({
        url: 'evaluation/fetch-faculties',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload,
    })
    
    pageData.evaluatedFaculties = response.data.evaluated_faculties
    pageData.peerEvaluatedFaculties = response.data.peer_evaluated_faculties

    const evaluatees = getFacultiesToEvaluate(response.data.records)
    pageData.evaluatees = evaluatees
  }

  function checkDepartmentId () {
    if (currentUser.role.role !== 'admin') {
      pageData.departmentId = currentUser.department_id
    } else {
      pageData.departmentId = ''
    }
  }

  function getFacultiesToEvaluate (records) {
    if (props.questionnaireType !== 'peer-to-peer') {
      return records
    } else {
      const sanitizedEvaluatees = []

      records.forEach((evaluatee) => {
        const evaluationEntry = pageData.peerEvaluatedFaculties?.find(ce => ce.evaluatee_id === evaluatee.id)
        if (!evaluationEntry) {
          sanitizedEvaluatees.push(evaluatee)
        }
        // console.log(evaluationEntry)
        // sanitizedEvaluatees.push(evaluatee)
      })

      return sanitizedEvaluatees
    }
  }

  /** check if valid to evaluate or not  */
  async function checkValidEvaluation () {
    const router = useRouter()
    const userRole = currentUser.role.role
    const redirectLink = `/${userRole}`
    if (!evaluationData.activeYear) {
      alert('No active year')
      router.push(redirectLink)
    } else {
      if (evaluationData.activeYear.status === 'closed') {
        alert('Status is closed. You are not allowed to evaluate yet.')
        router.push(redirectLink)
      }
    }
  }

  async function initialize () {
    await fetchUser()
    await fetchActiveYear()
    await checkValidEvaluation()
    await checkDepartmentId()
    await fetchFaculties()
    await fetchQuestionnaires()
  }

  async function handleSearch () {
    pageData.loaded = false
    await fetchFaculties()
    pageData.loaded = true
  }

  function formattedEvaluatee (evaluatee) {
    const filteredEvaluatee = pageData.evaluatedFaculties.find(cF => cF.evaluatee_id === evaluatee.id)
    const evaluated = filteredEvaluatee ? true : false
    return {
      ...evaluatee,
      evaluated: evaluated
    }
  }

  // logic for evaluation

  const evaluationData = reactive({
    evaluatee: {},
    evaluator: currentUser,
    activeYear: null,
    questionnaires: [],
  })

  async function fetchQuestionnaires () {
    const response = await api({
        url: '/evaluation/fetch-questionnaires',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    evaluationData.questionnaires = response.data.records
  }

  async function fetchActiveYear () {
    const response = await api({
        url: '/evaluation/fetch-active-year',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    evaluationData.activeYear = response.data.record
  }

  function beginEvaluation (evaluatee) {
    evaluationData.evaluatee = evaluatee
    evaluationData.evaluator = currentUser
    pageData.isEvaluating = true
  }

  const showEvaluationForm = computed(() => {
    if (props.questionnaireType === 'peer-to-peer') {
      return pageData.evaluatedFaculties.length ? false : true
    } else {
      return true
    }
  })

  async function handleBackToEvaluation () {
    pageData.isEvaluating = false
    await handleSearch()
  }


  onMounted(async () => {
    await initialize()
    pageData.loaded = true
  })

</script>

<style lang="scss" scoped>
* {
  box-sizing: border-box;
}
.cstm-button{
  border: none;
  padding: 5px 10px 5px 10px;
  border-radius: 5px;
  background: #FFCE82;
  color: rgba(0, 0, 0, 0.70);
  font-style: normal;
  font-weight: 700;
  margin-left: 20px;
}

.button-evaluated {
  opacity: 0.5;
}

.stack-container {
    background-color: #954087;
    border-radius: .5rem;
    margin: 1rem;
    padding: .5rem;
    max-width: 98%;
    color: white;
}

.table-container {
  overflow-x: scroll;
}

input,select {
  border: none;
  border-radius: 3px;
  padding: 4px;
  margin: 10px 0;
  width: 45%;
}

label {
  letter-spacing: 1px;
  display: block;
  font-weight: 600;
}

</style>
