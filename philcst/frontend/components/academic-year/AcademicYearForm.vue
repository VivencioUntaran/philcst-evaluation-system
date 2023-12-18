<template>
  <h1> {{ formTitle }} </h1>
  <div class="ay-form bgCard">
    <form @submit.prevent="handleSubmit">
      <div class="ay-form__group d-flex flex-column w-100 m-2">
        <label for="year"> Year * </label>
        <input 
          type="text"
          placeholder="ex: 2023-2024"
          v-model="formData.year"
          required
          name="year"
          :class="['ay-form__input', disabledInputs && 'ay-form__input--disabled']"
          class="w-100 p-1"
        />
      </div>
      <div class=" d-flex justify-content-between">
        <div class="ay-form__group w-100 m-2">
          <label for="semester"> Semester * </label>
          <select
            v-model="formData.semester"
            required
            name="semester"
            :class="['ay-form__input', disabledInputs && 'ay-form__input--disabled']"
            class="w-100 p-1"
          >
            <option value="" selected disabled> Select Semester </option>
            <option
              v-for="(item, index) in selections.semesters"
              :key="index"
            >
              {{ item }}
            </option>
          </select>
        </div>
        
        <div class="ay-form__group w-100 m-2">
          <label for="status"> Status * </label>
          <select
            v-model="formData.status"
            required
            name="status"
            :class="['ay-form__input', formData.system_default === 'no' && 'ay-form__input--disabled']"
            class="w-100 p-1"
          >
            <option value="" selected disabled> Select Status </option>
            <option
              v-for="(item, index) in selections.statuses"
              :key="index"
            >
              {{ item }}
            </option>
          </select>
        </div>
        <div class="ay-form__group w-100 m-2">
          <label for="system_default"> System Default * </label>
          <select
            v-model="formData.system_default"
            required
            name="system_default"
            class="w-100 p-1"
          >
            <option value="" selected disabled> Select System Default </option>
            <option
              v-for="(item, index) in selections.systemDefaults"
              :key="index"
            >
              {{ item }}
            </option>
          </select>
        </div>
      </div>

      <div 
        class="ay-form__error-container"
        v-if="formDataErrors"
      >
        <p class="text-danger"> {{ formDataErrors }} </p>
      </div>

      <div class="ay-form__button-container d-flex justify-content-end">
        <div class="m-3">
          <nuxt-link
            to="/admin/academic-year"
            class="m-2 text-danger fw-medium"
          >
            {{ props.formType === 'create' ? 'Back' : 'Cancel' }}
          </nuxt-link>
          <button type="submit" class="m-2">
            Submit
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
  const api = useApi()
  const router = useRouter()
  const route = useRoute()
  const props = defineProps({
    formType: {
      type: String,
      default: 'create'
    },
  })

  const loaded = ref(false)
  let record = reactive({})
  const disabledInputs = ref(false)
  const formTitle = ref('Create')

  onMounted(async () => {
    await initialize()
    loaded.value = true
  })

  async function initialize () {
    if (props.formType !== 'create') {
      disabledInputs.value = true
      // fetch current record
      await handleFetchCurrentRecord()
    }
  }

  const selections = {
    semesters: ['1st', '2nd'],
    statuses: ['open', 'closed'],
    systemDefaults: ['yes', 'no'],
  }
  const formData = reactive({
    year: '',
    semester: '',
    status: '',
    system_default: 'no',
  })
  const formDataErrors = ref('')

  async function handleCreate () {
    const sanitizedFormData = {
      ...formData,
      year: formData.year.trim(),
      status: formData.status
    }
    if (formData.system_default === 'no') {
      sanitizedFormData.status = 'closed'
    }
    try {
      const response = await api({
          url: '/admin/academic-years/create',
          method: 'POST',
          headers: {
              "Content-Type": "application/json",
          },
          data: sanitizedFormData,
      })

      alert('Academic year has been saved')
      router.push('/admin/academic-year')
    } catch (error) {
      formDataErrors.value = error?.response?.data?.errors
    }
  }

  async function handleFetchCurrentRecord () {
    const id = route.params.academicYearId
    try {
      const response = await api({
          url: `/admin/academic-years/${id}/show`,
          method: 'GET',
          headers: {
              "Content-Type": "application/json",
          },
      })
      record = response.data.record
      mapFormData(response.data.record)
    } catch (error) {
      console.log(error)
    }
  }

  function mapFormData (academicYear) {
    formData.year = academicYear.year
    formData.semester = academicYear.semester
    formData.status = academicYear.status
    formData.system_default = academicYear.system_default

    formTitle.value = `Update ${formData.year} (${formData.semester} Semester)`
  }

  async function handleUpdate () {
    const id = route.params.academicYearId
    const sanitizedFormData = {
      ...formData,
      status: formData.status
    }
    if (formData.system_default === 'no') {
      sanitizedFormData.status = 'closed'
    }
    try {
      const response = await api({
          url: `admin/academic-years/${id}/update`,
          method: 'PATCH',
          headers: {
              "Content-Type": "application/json",
          },
          data: sanitizedFormData,
      })
      alert('Data has been updated')
      router.push('/admin/academic-year')
    } catch (error) {
      console.log(error)
    }
  }

  async function handleSubmit () {
    if (props.formType === 'create') {
      await handleCreate()
    } else {
      await handleUpdate()
    }
  }
</script>

<style lang="scss" scoped>
select {
  text-transform: capitalize;
}
.ay-form {
  &__input {
    &--disabled {
      opacity: 0.6;
      pointer-events: none;
      user-select: none;
    }
  }
}

input, select {
  border: none;
  border-radius: 3px;
}
.bgCard {
  letter-spacing: 1px;
}

button {
  letter-spacing: 1px;
  border: none;
  padding: 5px 15px;
  border-radius: 2px;
  background-color: rgb(152, 72, 184);
  color: white;
  font-weight: 400;
}
</style>