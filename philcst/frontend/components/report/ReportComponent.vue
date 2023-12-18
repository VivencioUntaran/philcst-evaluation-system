<template>
  <div class="evaluation-component bgCard">
    <div v-if="pageData.loaded">
      <div 
        class="evaluation-component__header"
        v-if="!pageData.isViewing"
      >
        <div class="stack-container">
          <div class="d-flex justify-content-between">
            <DepartmentSelector 
              v-model="pageData.departmentId"
              @valueChanged="handleSearch"
              :disabled="props.user.role.role !== 'admin'"
            />
          </div>
          <div class="d-flex justify-content-between">
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
                  placeholder="Search instructor or dean"
                  class="mx-0 w-100"
                >
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button
              @click="handleSearch"
              class="cstm-button mx-2 px-4"> Search
            </button>
          </div>
        </div>
      </div>
      
      <div 
        class="evaluation-component__body"
        v-if="!pageData.isViewing"
      >
        <div class="table-container">
            <table 
              class="table position-relative" 
              v-if="pageData.evaluatees.length"
            >
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col" class="text-align-left">Name </th>
                  <th scope="col">Department</th>
                  <th scope="col">Role</th>
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
                    <td> {{ evaluatee.department.department }} </td>
                    <td> {{ evaluatee.role.role.toUpperCase() }} </td>
                    <td>
                        <button
                          :class="['cstm-button m-1', 'tdButton']"
                          @click="toggleReport(evaluatee)"
                        >
                          View Report
                        </button>
                    </td>
                  </tr>
              </tbody>
            </table>
            <div v-else>
              <p> No record found. </p>
            </div>
          </div>
      </div>

      <!--- Faculty Report Container -->
      <ReportFacultyReport
        v-if="pageData.isViewing"
        :evaluatee="facultyReport.evaluatee"
      />
    </div>
    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script setup>
  const api = useApi()
  
  const props = defineProps({
    user: {
      type: Object,
      default: () => {}
    },
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
    departments: [],
    roles: [
      { label: 'Faculty', value: 'faculty' },
      { label: 'Dean', value: 'dean' },
    ],
    selectedRole: '',
    selectedAcademicYear: '',
    isViewing: false,
    searchQuery: '',
  })

  const isAdmin = computed(() => {
    return props.user && props.user.role.role === 'admin' ? true : false
  })

  async function fetchFaculties () {
    const user = props.user
    const payload = {
      role: user.role.role,
      department_id: pageData.departmentId,
      evaluatee_name: pageData.searchQuery,
      evaluatee_role: pageData.selectedRole,
    }

    const response = await api({
        url: 'admin/reports/fetch_faculties',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload,
    })
    
    pageData.evaluatedFaculties = response.data.evaluated_faculties
    pageData.evaluatees = response.data.records
  }

  async function fetchDepartments () {
    const response = await api({
        url: 'departments',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    pageData.departments = response.data.records
  }

  async function handleSearch () {
    pageData.loaded = false
    await fetchFaculties()
    pageData.loaded = true
  }

  async function initialize () {
    await autoPopulateDepartmentId()
    await fetchDepartments()
    await fetchFaculties()
  }

  async function autoPopulateDepartmentId () {
    if (props.user.role.role !== 'admin') {
      pageData.departmentId = props.user.department_id
    }
  }

  function checkEnabledDepartment () {
    const user = props.user
    return true
  }

  onMounted(async () => {
    await initialize()
    pageData.loaded = true
  })

  // report logic

  const facultyReport = reactive({
    evaluatee: {}
  })

  function toggleReport (evaluatee) {
    facultyReport.evaluatee = evaluatee
    pageData.isViewing = true
  }

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
  margin: 10px;
  width: 45%;
}

label {
  letter-spacing: 1px;
  display: block;
  font-weight: 600;
}
</style>
