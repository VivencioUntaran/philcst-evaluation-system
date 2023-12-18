<template>
    <div 
        v-for="(prop, index) in elementProps" 
        class="bgCard"
        :key="index"
    >
        <div class="mb-4 text-dark">
            <h2>{{ prop.addCriteria.title }}</h2>
            <!-- <form 
                @submit.prevent="saveData" 
                id="myform"
            >
                <input 
                    type="text"
                    v-model="formData.name"
                    class="addCriteriaInput"
                >
                <button 
                    type="submit" 
                    class="btn-yellow submitBtn"
                >
                    + Add New
                </button>
                
                <input 
                  v-for="(point, index) in formData.points"
                  type="text"
                  :name="`point${index}`"
                  :id="`point-${index}`"
                  required
                  class="addPoints"
                  v-model="point.value"
                >
        </form> -->

        </div>

        <div
					v-for="(item, index) in criteriaListItems" 
					:item="item"
					:key="index"
        >
            <div class="stack-container text-dark">
                <div class="criteria-container">
                  {{ item.name }}
                </div>
                <div>
									<nuxt-link 
										:to="`/admin/questionnaires/${$route.params.questionnaireSlug}/${item.id}`" 
										class="btn-yellow text-decoration-none text-dark"
									>
										View Questions
									</nuxt-link>
									<!-- <button 
										type="button" 
										class="btn-yellow delBtn"
										@click="deleteCriteria(item)"
									>
										Delete
									</button> -->
							</div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    questionnaire: {
        type: Object,
        default: () => {},
    },
})
const elementProps = [{
    addCriteria: {
        title: 'Criterias'
    }
}]

import { ref, onMounted } from 'vue'

const criteriaListItems = ref([])

const api = useApi()

const getData = async () => {
      try {
        const response = await api({
            url: `admin/questionnaires/${props.questionnaire.id}/show`,
            method: 'GET',
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        criteriaListItems.value = response.data.record.criterias; // Assign the fetched data to the 'items' ref   

    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
onMounted(getData)

const formData = ref({
    name: '',
    questionnaire_id: props.questionnaire.id,
    points: [
      { value: '' },
      { value: '' },
      { value: '' },
      { value: '' },
      { value: '' },
    ]
})

async function saveData () {
  try {
      const response = await api({
          url: 'admin/criterias/create',
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
  formData.value.name = ''
  formData.value.points.forEach(point => {
    point.value = ''
  })
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
.btn-yellow {
	border-radius: 4px;
}

.hideBtn {
    display: none;
}

.stack-container {
    background-color: #D9D9D9;
    border-radius: 3px;
    margin: .5rem;
    padding: 1rem;
    max-width: 98%;
    color: white;
    display: flex;
    justify-content: space-between;
}

.addCriteriaInput{
    width:91%;
    border: none;
    border-radius: 1rem;
    padding: 5px 15px 5px 15px;
    margin-right: 5px;
}
.delBtn {
    margin-left: 5px;
}
.submitBtn {
  margin-top: 8px;
}
.addPoints {
  display: block;
}

@media screen and (max-width: 730px) {
  .stack-container {
    flex-direction: column;
  }
  .criteria-container {
    margin-bottom: 10px;
  }
}
</style>