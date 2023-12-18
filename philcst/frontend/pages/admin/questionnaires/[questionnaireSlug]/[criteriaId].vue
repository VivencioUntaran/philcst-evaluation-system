<template>
<div>
  <div v-if="loaded && criteria">
    <h2>Criteria Questions</h2>
    <div
        class="bgCard"
    >
        <div>
          <!-- <h2>Add Questions</h2>
          <form
              @submit.prevent="saveData"
              id="myform"
          >
              <input
                type="text"
                v-model="formData.question"
              >
              <pre>{{ formData.name }}</pre>
              <button
                  type="submit"
                  class="btn-yellow submitBtn"
              >
                + Add New
              </button>
          </form> -->
        </div>
        <h4 class="questionListTitle">Question List</h4>

        <div class="stack-container">
          <div class="criteriaName mb-2 mt-2">{{ criteria.name }}</div>

          <div>
            <label>Point Guide:</label>
            <div class="pointContainer">
              <div
                v-for="(point, index) in criteria.criteria_points"
                class="point"
              >
                {{ point.point }}
              </div>
            </div>
          </div>

        </div>
        <div
            v-for="(question, index) in criteria.questions"
            :key="index"
        >
            <div class="questionItemContainer">
               {{ index + 1 }}. {{ question.question }}
            </div>
        </div>
    </div>
  </div>
  <div v-if="!criteria">
    Criteria Not Found
  </div>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const api = useApi()

const route = useRoute()
const router = useRouter()
const criteriaId = route.params.criteriaId
const title = ref('Loading...')
const criteria = ref({})
const loaded = ref(false)

const getData = async () => {
    try {
      const response = await api({
          url: `/admin/criterias/${criteriaId}/show`,
          method: 'GET',
          headers: {
              "Content-Type": "multipart/form-data",
          },
      });

      criteria.value = response.data.record
      title.value = criteria.name
      loaded.value = true

      // criteriaListItems.value = response.data.record.criterias; 

  } catch (error) {
      console.error('Error fetching data:', error);
  }
};
onMounted(getData)

const formData = ref({
  question: '',
  criteria_id: criteriaId,
})

async function saveData () {
try {
    const response = await api({
        url: 'admin/questions/create',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: formData.value
    });


} catch (error) {
    console.error('Error fetching data:', error);
} finally {
  clearForm()
  getData()
  }
}

function clearForm () {
formData.value.question = ''
}

async function deleteCriteria (criteria) {
const confirmmationMessage = confirm(`Are you sure you want to delete ${criteria.name}?`)
// check if confirmed
if (!confirmmationMessage) return
// begin deletion of data
try {
  const response = await api({
      url: `/admin/criterias/${criteria.id}/delete`,
      method: 'DELETE',
      headers: {
          "Content-Type": "application/json",
      },
  });

} catch (error) {
    console.error('Error fetching data:', error);
} finally {
  getData()
}

}
</script>

<style lang="scss" scoped>

label {
  letter-spacing: 1px;
  font-weight: 500;
}
.hideBtn {
  display: none;
}

.stack-container {
  background-color: #954087;
  border-radius: .5rem;
  margin: 1rem 1rem 0px 1rem;
  padding: 1rem;
  max-width: 98%;
  color: white;
  display: flex;
  align-items:center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.criteria-container {
  background-color: #954087;
  border-radius: .5rem;
  margin: 1rem;
  padding: .5rem;
  max-width: 98%;
  color: white;

  input{
    width:91%;
    border: none;
    border-radius: 1rem;
    padding: 5px 15px 5px 15px;
    margin-right: 5px;
  }

}

.questionItemContainer {
  background-color: #D9D9D9;
  margin: 5px 1rem -2px 1rem;
  padding: .5rem;
  max-width: 98%;
}

.pointContainer {
  display: flex;
  text-align: center;
  justify-content: space-around;
}

.point {
  background-color: #D9D9D9;
  padding: 3px 12px;
  margin: 5px;
  border-radius: 2px;
  color: black;

}

.criteriaName {
  letter-spacing: 1px;
  font-weight: 500;
  display: inline-block;
  margin-right: 20px;
}

.questionListTitle {
  margin: 1rem;
}

.delBtn {
  margin-left: 5px;
}

.submitBtn {
  margin-top: 8px;
}

@media screen and (max-width: 480px) {
  .stack-container {
    overflow-x: scroll;
  }
}
</style>