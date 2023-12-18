<template>
  <div>
    <div>
        <nav>
            <h2>
                {{ title }}
            </h2>
        </nav>
        <CriteriaList 
          :questionnaire="questionnaire"
          v-if="loaded"
        />
    </div>
  </div>

  </template>
  
<script setup>
const api = useApi()
const questionnaires = ref({})
const title = ref('Loading...')
const questionnaire = ref({})
const loaded = ref(false)

const getData = async () => {
  try {
    const response = await api({
        url: 'admin/questionnaires/',
        method: 'GET',
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });
    questionnaires.value = response.data.records; // Assign the fetched data to the 'items' ref   

  } catch (error) {
      console.error('Error fetching data:', error);
  }
}


const route = useRoute()
const router = useRouter()
const slug = route.params.questionnaireSlug
// const validPages = ['peer-to-peer', 'dean-to-faculty', 'admin-to-faculty', 'student-to-faculty']


function checkValidPage () {
  const record = questionnaires.value.find(cq => cq.slug === slug)
  if (!record) {
    router.push('/admin/questionnaires/error')
  }
  title.value = record.name
  questionnaire.value = record
}

onMounted(async () => {
  await getData()
  checkValidPage()
  loaded.value = true
})

</script>



<style lang="scss" scoped>
input {
  display: block;
}

.hideBtn {
  display: none;
}

.stack-container {
  background-color: #954087;
  border-radius: .5rem;
  margin: 1rem;
  padding: .5rem;
  max-width: 98%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.delBtn {
  margin-left: 5px;
}



</style>

