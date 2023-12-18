<template>
    <div>
      <div class="transition">
        <nav>
            <h2>Academic Year</h2>
        </nav>

        <div class="bgCard">
            <form action="" @submit.prevent="handleSearch">
              <input
                  class="cstm-input"
                  type="text"
                  placeholder="Search"
                  v-model="searchData.keyword"
              >
              <button
                type="submit"
                @click="handleSearch"
                class="btn-yellow mx-2 px-3 py-2"
              > Search </button>
            </form>
            <br>
            <nuxt-link 
              class="btn-add mx-2 px-3 py-2"
              to="/admin/academic-year/create"
            >
              +Add New
            </nuxt-link>
            <div class="table-container" v-if="loaded">
                <table class="table position-relative text-start">
                    <thead>
                        <tr>
                          <th scope="col">Academic Year </th>
                          <th scope="col">Semester</th>
                          <th scope="col">Status</th>
                          <th scope="col">System Default</th>
                          <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="academicYears.length">
                      <tr 
                        v-for="(item, index) in academicYears"
                        :key="index"
                      >
                        <td> {{ item.year }} </td>
                        <td>{{ item.semester }}</td>
                        <td 
                          :class="['academic-year__status', getTheme(item, 'status')]"
                        > {{ item.status }} </td>
                        <td :class="['academic-year__status', getTheme(item, 'system_default')]"> {{ item.system_default }} </td>
                        <td class="d-flex flex-wrap">
                            <nuxt-link 
                              class="edit-btn m-1"
                              :to="`/admin/academic-year/${item.id}/edit`"
                            > Edit </nuxt-link>
                            <button
                              class="m-1" 
                              :class="['delete-btn', item.evaluation_entries.length && 'btn-disabled', item.system_default.toLowerCase() === 'yes' && 'btn-disabled']"
                              @click="handleDelete(item)"
                            > Delete</button>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <p>No records found.</p>
                    </tbody>
                </table>
            </div>
            <div v-else class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
      </div>
    </div>
</template>

<script setup>
  const api = useApi()

  const loaded = ref(false)
  onMounted(async () => {
    await initialize()
    loaded.value = true
  })

  async function initialize () {
    await fetchAcademicYears()
  }

  let academicYears = reactive([])
  async function fetchAcademicYears () {
    const response = await api({
        url: '/admin/academic-years',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    academicYears = response.data.records
    
  }

  function getTheme (item, type) {
    if (type === 'status') {
      return item.status === 'open' ? 'academic-year__status--green' : 'academic-year__status--gray'
    } else {
      return item.system_default === 'yes' ? 'academic-year__status--green' : 'academic-year__status--gray'
    }
  }

  const searchData = reactive({
    keyword: '',
  })
  async function handleSearch () {
    loaded.value = false
    const response = await api({
        url: 'admin/academic-years/search',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: searchData,
    })
    academicYears = response.data.records
    loaded.value = true
  }

  async function handleDelete (item) {
    const title = `${item.year} (${item.semester} semester)`
    if (confirm(`Are you sure you want to delete ${title}?`)) {
      try {
        const response = await api({
          url: `admin/academic-years/${item.id}/delete`,
          method: 'DELETE',
          headers: {
              "Content-Type": "application/json",
          }
        })
        alert('Data has been deleted')
        handleSearch()
      } catch(error) {
        console.log('an error occured')
      }
    }
  }
</script>

<style lang="scss" scoped>
a {
  text-decoration: none;
}

.table-container {
    margin-top: 1rem;
    overflow-x: scroll;
    border-radius: 1rem;
}

.edit-btn {
    border: none;
    background-color: #3A8484;
    border-radius: 5px;
    padding: 5px 1rem;
    color: #fff;
}

.delete-btn {
    border: none;
    background-color: #FE6162;
    border-radius: 5px;
    padding: 5px .5rem;
    color: #fff;

}

.btn-disabled {
  opacity: 0.5;
  pointer-events: none;
}

.btn-add {
  color: #ffffff;
  border-radius: 5px;
  background: #954087;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}
.academic-year {
  &__status {
    text-transform: capitalize;
    &--green {
      color: #28a745;
    }
    &--gray {
      color: #6c757d;
    }
  }
}

</style>