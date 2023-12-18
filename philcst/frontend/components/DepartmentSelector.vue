<template>
<div :class="['department-selector w-100', `department-selector--${customClass}`]">
  <div 
    :class="['d-flex w-100 flex-column', 'department-selector__dropdown-container', props.disabled && 'no-click disabled']"
    v-if="componentData.loaded"
  >
    <label for="" class="fw-medium"> Select department </label>
    <select
      name="department"
      v-model="value"
      @change="handleChange"
      :disabled="disabled"
    >
      <option 
        value=""
        selected
        v-if="showAll"
        :disabled="disabledAll"
      > {{ allPlaceholder }} </option>
      <option
        v-for="(department, index) in componentData.departments"
        :key="index"
        :value="department.id"
      >
        {{ department.department }}
      </option>
    </select>
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</div>
</template>

<script setup>
  const props = defineProps({
    modelValue: {},
    showAll: {
      type: Boolean,
      default: true,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    allPlaceholder: {
      type: String,
      default: 'All Departments',
    },
    disabledAll: {
      type: Boolean,
      default: false,
    },
    customClass: {
      type: String,
      default: '',
    }
  })
  const emit = defineEmits(['update:modelValue', 'valueChanged'])
  const api = useApi()


  const value = computed({
    get() {
      return props.modelValue
    },
    set(value) {
      emit('update:modelValue', value)
    }
  })

  const componentData = reactive({
    academicYears: [],
    loaded: false,
    disabled: false,
  })

  async function fetchDepartments () {
    const response = await api({
        url: 'departments',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
        }
    })
    componentData.departments = response.data.records
  }

  function handleChange () {
    emit('valueChanged')
  }

  onMounted(async () => {
    await fetchDepartments()
    componentData.loaded = true
  })
</script>

<style lang="scss" scoped>
input,select {
  border: none;
  border-radius: 3px;
  padding: 4px;
  margin: 10px 0;
  width: 100%;
}

.department-selector {
  &--user-info {
    label {
      color: black;
      font-weight: 200 !important;
    }
  }
}
</style>