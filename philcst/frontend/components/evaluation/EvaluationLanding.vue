<template>
  <div>
    <div 
      class="transition"
      v-if="pageData.loaded"
    >
      <nav>
        <h2>
            Dashboard
        </h2>
      </nav>
      <!-- MAIN CONTENT -->
      <div>
        <!-- HEADER CONTENT -->
        <div>
            <h5> {{ message }} </h5>
        </div>
        <!-- HEADER CONTENT END -->


        <!-- DASHBOARD CARD CONTENT-->
        <div class="bgCard" v-if="pageData.academicYear">
            <Dashboard :dashboardItems="dashboardItems"/>
        </div> 
        <div v-else>
          <p>No default academic year. </p>
          <p v-if="pageData.currentUser?.role.role === 'admin'">Click <nuxt-link to="/admin/academic-year">here</nuxt-link> to set default academic year or create one if you don't have any. </p>
        </div>
        <!-- DASHBOARD CARD CONTENT END-->
      </div>
      <!-- MAIN CONTENT END-->
    </div>
    <div v-else class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script setup>
const { getCurrentUser } = useCustomAuth()
const api = useApi()
const message = computed(() => {
  const user = getCurrentUser()
  return `Hello ${user.name}. Welcome to Philcst Evaluation System.`
})

const pageData = reactive({
  currentUser: null,
  loaded: false,
  academicYear: null, // null {}
})

onMounted(async () => {
  await initialize()
  pageData.loaded = true
})

async function initialize () {
  const user = getCurrentUser()
  const response = await api({
      url: 'evaluation/fetch-active-year',
      method: 'GET',
      headers: {
          "Content-Type": "application/json",
      },
  })
  pageData.academicYear = response.data.record
  pageData.currentUser = user
  if (response.data.record) {
    populateDashboardItems() 
  }
}

// -->DATA that is passed 
const dashboardItems = []

function populateDashboardItems () {
  const user = getCurrentUser()
  const academicYear = pageData.academicYear
  const academicYearObj = {
      text: 'Academic Year:',
      acadYear: academicYear.year,
      semester: `${academicYear.semester} Semester Evaluation`,
      evalStatus: `Status: ${academicYear.status.toUpperCase()}`,
      cardColor: 'blue-box',
      image: {
          src: '/images/acad-year.svg',
          width: "240px",
      },
  }

  const evalObject = {
      text: 'Evaluate', 
      cardColor: 'yellow-box',
      image: {
          src: '/images/evaluate.svg',
          width: "240px",
      },
      btn: {
          label: 'Click here',
          show: academicYear.status === 'open',
          link: `${user.role.role}/evaluate-page/`,
          evalStatus: academicYear.status
      },
      
  }

  dashboardItems.push(academicYearObj)
  dashboardItems.push(evalObject)
}

</script>

<style>
* {
  box-sizing: border-box;
}
/* DASHBOARD COMPONENT INJECTED */
.heading {
  background-color: #e9e9e9;
  font-style: italic;
  border-radius: 1rem;
  margin-top: 1.5rem;
  max-width: 98%;
  padding: 2rem;

  &:hover {
      background-color: #cecece;    
  }
}

.bgCard {
  background-color: #CAA7D3;
  border-radius: 2rem;
  margin-top: 1.5rem;
  margin-bottom: 2rem;
  padding: 1.5rem;
  max-width: 98%;

  &:hover {
      background-color: #ba93c4; 
  }
}

.imgFluid {
  max-width: 70%
}

</style>