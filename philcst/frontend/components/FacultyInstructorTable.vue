<template>
  <h1> {{ title }} </h1>
  <div class="fi-table bgCard">
    <div class="fi-table__head">
      <nuxt-link :to="props.addButton.url">
        <button class="add-dean-btn">{{ props.addButton.label }}</button>
      </nuxt-link>

      <div class="stack-container mt-2 mb-3">
        <form 
          class="w-100 px-2"
          @submit.prevent="handleSearch"
        >
          <label class="search-label">Search</label>
          <div class="d-flex align-items-center justify-content-between">
            <input
              type="text"
              name="seachQuery"
              v-model="pageData.searchQuery"
              :placeholder="isAdmin ? 'Search dean' : 'Search faculty'"
              class="mx-0 w-100"
            >
            <button 
              class="cstm-button mx-2 btn-yellow"
              type="submit"
            > Search </button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="pageData.loaded">
      <div class="table-container">
        <table 
          class="table table-bordered table-striped position-relative" 
          v-if="pageData.records.length"
        >
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name </th>
              <th scope="col">Department</th>
            </tr>
          </thead>
          <tbody>
              <tr
                v-for="(record, index) in pageData.records"
                :key="index"
              >
                <th scope="row"> {{ index + 1 }} </th>
                <td> {{ record.name }} </td>
                <td> {{ record.department?.department }} </td>
              </tr>
          </tbody>
        </table>
        <div v-else>
          <p> No record found. </p>
        </div>
      </div>
    </div>

    <LoaderComponent v-else />
  </div>
</template>

<script setup>
  const api = useApi()
  const { getCurrentUser } = useCustomAuth()
  const props = defineProps({
    title: {
      type: String,
      default: '',
    },
    /**
     * label: 
     * url: 
     */
    backButton: {
      type: Object,
      default: () => {},
    },
    addButton: {
      type: Object,
      default: () => {},
    },
  })

  const pageData = reactive({
    loaded: false,
    records: [],
    searchQuery: '',
  })

  const isAdmin = computed(() => {
    const user = getCurrentUser()
    return user.role.role === 'admin'
  })

  onMounted(async () => {
    await initialize()
    pageData.loaded = true
  })

  async function initialize () {
    await fetchFacultyInstructorData()
  }

  async function fetchFacultyInstructorData () {
    const payload = await buildPayload()
    
    const response = await api({
        url: '/admin/faculty_instructors/fetch_faculties',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload,
    })

    pageData.records = response.data.records
  }

  async function buildPayload () {
    const currentUser = getCurrentUser()
    const payload = {
      role: currentUser.role.role === 'admin' ? 'dean' : 'faculty', // fetch dean accounts if admin. faculty accounts if dean
      department_id: currentUser.department_id,
      search_query: pageData.searchQuery,
    }
    return payload
  }

  async function handleSearch () {
    pageData.loaded = false
    await fetchFacultyInstructorData()
    pageData.loaded = true
  }
</script>


<style lang="scss" scoped>
.add-dean-btn {
  border: none;
  padding: 5px 15px;
  border-radius: 3px;
  background-color: purple;
  color:rgb(240, 240, 240);
  letter-spacing: 1px;
}

.btn-yellow {
  letter-spacing: 1px;
  padding: 5px 20px;
}

.search-label {
  letter-spacing: 1px;
  font-weight: 600;
  margin-bottom: 2px;
}

input {
  border: none;
  border-radius: 3px;
  padding: 5px;
  padding-left: 10px;
}

</style>