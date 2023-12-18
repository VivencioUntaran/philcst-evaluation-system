<template>
  <div 
    class="faculty-report"
    id="faculty-report-component"
  >
    <div v-if="facultyReportData.loaded">
      <!--- place info here -->
      <p class="info-style"><b>Name:</b> {{ props.evaluatee.name }}</p>
      <p class="info-style"><b>Department</b>: {{ props.evaluatee.department.department }}</p>
      <p class="info-style mb-3"><b>Role</b>: {{ props.evaluatee.role.role.toUpperCase() }}</p>
      <div class="stack-container faculty-report__content">
        <div class="faculty-report__header">
          <div class="d-flex justify-content-between">
            <div class="w-100 px-2">
              <label for="" class="fw-bold"> Questionnaire Type: </label>
              <select
                name="role"
                v-model="facultyReportData.questionnaireId"
                @change="fetchFacultyEvaluation"
                class="w-100 mx-0"
              >
                <option
                  v-for="(item, index) in facultyReportData.questionnaires"
                  :key="index"
                  :value="item.id"
                >
                  {{ item.name }}
                </option>
              </select>
            </div>
            <div class="w-100 px-2">
              <label for="" class="fw-bold"> Academic Year: </label>
              <select
                name="role"
                v-model="facultyReportData.academicYearId"
                @change="fetchFacultyEvaluation"
                class="w-100 mx-0"
              >
                <option
                  v-for="(item, index) in facultyReportData.academicYears"
                  :key="index"
                  :value="item.id"
                >
                  {{ `${item.year} - ${item.semester} semester` }}
                </option>
              </select>
            </div>
          </div>
        </div>
        <div v-if="facultyReportData.reportData?.criterias.length">
          <ReportCriteriaSummary
            :reportData="facultyReportData.reportData"
            :currentAcademicYear="currentAcademicYear"
          />
        </div>
        <div v-else>
          <p> No report data </p>
        </div>
      </div>
    </div>

    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script setup>
  const api = useApi()
  const { getCurrentUser } = useCustomAuth()
  
  const props = defineProps({
    evaluatee: {
      type: Object,
      default: () => {}
    },
  })
  
  const facultyReportData = reactive({
    loaded: false,
    academicYears: [],
    academicYearId: '',
    questionnaires: [],
    questionnaireId: '',
    reportData: {},
  })

  const currentAcademicYear = computed(() => {
    return facultyReportData.academicYears.find(cay => cay.id === facultyReportData.academicYearId)
  })

  onMounted(async () => {
    await initialize()
    facultyReportData.loaded = true
  })

  async function initialize () {
    await fetchAcademicYears()
    await fetchQuestionnaires()
    await fetchFacultyEvaluation()
  }

  async function fetchAcademicYears () {
    const response = await api({
        url: 'admin/academic-years',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    const records = response.data.records
    facultyReportData.academicYears = records
    facultyReportData.academicYearId = records[0].id // first element
  }

  async function fetchQuestionnaires () {
    const response = await api({
        url: '/admin/questionnaires',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    const records = response.data.records
    const newRecords = getUserQuestionnaireItems(records)
    facultyReportData.questionnaires = newRecords
    facultyReportData.questionnaireId = newRecords[0].id // first element
  }

  function getUserQuestionnaireItems (records) {
    const user = getCurrentUser()
    const newQuestionnaires = []

    records.forEach((record) => {
      if (user.role.role === 'admin') {
        newQuestionnaires.push(record)
      } else {
        const deanItems = ['student-to-faculty', 'dean-to-faculty', 'peer-to-peer']
        if (deanItems.includes(record.slug)) {
          newQuestionnaires.push(record)
        }
      }
    })

    return newQuestionnaires
  }

  async function fetchFacultyEvaluation () {
    facultyReportData.loaded = false
    const payload = {
      academic_year_id: facultyReportData.academicYearId,
      questionnaire_id: facultyReportData.questionnaireId,
      evaluatee_id: props.evaluatee.id,
    }

    const response = await api({
        url: '/admin/reports/get_evaluation_report',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload,
    })
    const record = response.data.record
    facultyReportData.reportData = record
    facultyReportData.loaded = true
  }

</script>

<style lang="scss" scoped>

.stack-container {
  background-color: #954087;
  border-radius: .5rem;
  color: white;
  padding: 10px;
  letter-spacing: 1px;
}

select {
  border: none;
  border-radius: 2px;
  padding: 3px;
}

.info-style {
  letter-spacing: 1px;
  color: rgb(0, 0, 0);
  font-weight: 400;
  margin: 3px 0px;
  font-family: 'Lucida Sans';
}

.faculty-report {
  &--exporting {
    label {
      color: black;
    }
    select {
      /* for Firefox */
      -moz-appearance: none;
      /* for Chrome */
      -webkit-appearance: none;
    }
  }
}
</style>