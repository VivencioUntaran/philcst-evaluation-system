<template>
    <div :class="['user-information-form', `user-information-form--${customClass}`]">
      <div 
        class="transition w-100" 
        v-if="loaded"
      >
        <div class="bgCard-none">
          <form
            @submit.prevent="handleSubmit"
            class="childFormContainer"
          >
            <div
              v-for="(item, key) in forms"
              :key="key"
              class="user-information-form__form-group d-flex flex-column"
            >
              <label> {{ item.label }} {{ item.required ? '*' : '' }} </label>
              <input
                v-if="item.type === 'text' || item.type === 'password' || item.type === 'email'"
                :type="item.type"
                v-model="formData[item.vModel]"
                :placeholder="item.placeholder"
                :required="item.required"
                :disabled="item.disabled"
              />

              <div v-if="item.required">
                <span
                  :class="['text-danger', errorClass]"
                  v-if="getErrorInfo(item).show"
                >
                  {{ getErrorInfo(item).message }}
                </span>
              </div>
            </div>

            <div 
              :class="['user-information-form__selector-container']"
              v-if="props.role !== 'admin' && props.formType === 'create'"
            >
              <DepartmentSelector
                v-model="formData.departmentId"
                :allPlaceholder="'Select a department'"
                required
                :disabledAll="true"
                :disabled="props.disabledSelector"
                :customClass="'user-info'"
              />
              <span 
                :class="['text-danger', errorClass]"
                v-if="getErrorInfo(departmentObj).show"
              >
                {{ getErrorInfo(departmentObj).message }}
              </span>
            </div>

            <div class="user-information-form__submit-container">
              <button 
                type="submit" 
                class="w-100"
              >
                Submit
              </button>
            </div>
          </form>

        </div>
      </div>
      <LoaderComponent v-else/>
    </div>
</template>

<script setup>
  const api = useApi()
  const { getCurrentUser, saveAuthDetails } = useCustomAuth()
  const props = defineProps({
    formType: {
      type: String,
      default: 'create', // create update
    },
    role: {
      type: String,
      default: '', // student, dean
    },
    customClass: {
      type: String,
      default: '', // user-registration, user-profile, create-dean
    },
    backLink: {
      type: String,
      default: '/',
    },
    disabledSelector: {
      type: Boolean,
      default: false,
    },
  })

  const loaded = ref(false)
  
  let formData = reactive({
      fullName: '',
      lastName: '',
      email: '',
      departmentId: '',
      password: '',
      confirmPassword: '',
      role_id: '',
      studentNumber: '',
  })
  const errorClass = 'dvCcRVqCRP'

  const pressedSubmit = ref(false)

  const forms = reactive([
    {
      type: 'text',
      label: 'Full Name',
      required: true,
      vModel: 'fullName',
      name: 'full_name',
      placeholder: 'Enter full name',
      disabled: false,
    },
    {
      type: 'email',
      label: 'Email',
      required: true,
      vModel: 'email',
      name: 'email',
      placeholder: 'Enter email',
      disabled: false,
    },
    {
      type: 'password',
      label: 'Password',
      required: props.formType === 'create',
      vModel: 'password',
      name: 'password',
      placeholder: 'Enter password',
      disabled: false,
    },
    {
      type: 'password',
      label: 'Confirm Password',
      required: props.formType === 'create',
      vModel: 'confirmPassword',
      name: 'confirm_password',
      placeholder: 'Confirm password',
      disabled: false,
    }
  ])

  const departmentObj = {
    vModel: 'departmentId',
    name: 'department_id',
  }
  const studentNumberObj = {
    type: 'text',
    label: 'Student Number',
    required: true,
    vModel: 'studentNumber',
    name: 'student_number',
    placeholder: 'Enter student number',
    disabled: props.formType === 'update',
  }

  onMounted(async () => {
    await fetchCurrentRole()
    await checkIfStudent()
    await checkIfupdate()
    await autoSetDepartmentId()
    loaded.value = true
  })

  async function checkIfStudent() {
    if (props.role === 'student') {
      forms.unshift(studentNumberObj)
    }
  }

  async function autoSetDepartmentId () {
    const currentUser = getCurrentUser()
    formData.departmentId = currentUser?.department_id ? currentUser.department_id : ''
  }

  async function checkIfupdate () {
    if (props.formType === 'update') {
      await mapCurrentUserData()
    }
  }

  async function mapCurrentUserData () {
    const currentUser = getCurrentUser()
    formData = {
      ...formData,
      departmentId: currentUser.department_id,
      fullName: currentUser.name,
      studentNumber: currentUser.student_number,
      email: currentUser.email,
      user_id: currentUser.id,
    }
    console.log(formData, currentUser)
  }

  async function fetchCurrentRole () {
    const payload = {
      role: props.role,
    }
    const response = await api({
        url: '/user_auth/filter_role',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload
    })
    const record = response.data.record
    formData.role_id = record.id
  }

  function getErrorInfo (item) {
    const toReturn = {
      show: false,
      message: '',
    }
    const readableName = item.name.replace(/(^\w)/g, g => g[0].toUpperCase()).replace(/([-_]\w)/g, g => " " + g[1].toUpperCase()).trim()

    switch(item.name) {
      case 'confirm_password':
        if (!formData.confirmPassword.length && formData.password.length) {
          toReturn.show = true
          toReturn.message = `${readableName} is required`
        } else if ((formData.password.length && formData.confirmPassword.length) && formData.password !== formData.confirmPassword) {
          toReturn.show = true
          toReturn.message = 'Passwords do not match'
        }
        break
      case 'department_id':
        toReturn.message = `The ${readableName} field is required`
        if (!formData[item.vModel] && pressedSubmit.value) {
          toReturn.show = true
        } else {
          toReturn.show = false
        }
        break
      default:
        toReturn.message = `The ${readableName} field is required`
        const hasValue = formData[item.vModel]?.length
        if (!hasValue && pressedSubmit.value) {
          toReturn.show = true
        } else {
          toReturn.show = false 
        }
        break
    }

    return toReturn
  }

  async function handleSubmit () {
    pressedSubmit.value = true
    setTimeout(async () => {
      const valid = await handleValidation()
      if (!valid) return

      if (props.formType === 'create') {
        handleSave()
      } else {
        handleUpdate()
      }
    }, 200)
  }

  async function handleValidation () {
    const errors = document.querySelectorAll(`.${errorClass}`)
    if (errors.length) {
      return false
    } else {
      return true
    }
  }

  async function handleSave () {
    const defaultDepartmentId = formData.departmentId
    const payload = {
      ...formData,
      role: props.role,
      name: formData.fullName,
      password_confirmation: formData.confirmPassword,
      department_id: formData.departmentId,
      readable_role: props.role,
      student_number: formData.studentNumber,
    }

    if (props.disabledSelector) {
      payload.department_id = defaultDepartmentId
    }
    
    try {
      const response = await api({
        url: '/user_auth/register',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload
      })
      alert('Data has been saved')
      if (props.backLink.length) {
        const router = useRouter()
        router.push(props.backLink)
      }
      clearForm()
    } catch (error) {
      const formattedError = error?.response?.data?.message
      if (formattedError) {
        alert(formattedError)
      }
    }
  }

  async function handleUpdate () {
    const payload = {
      ...formData,
      name: formData.fullName,
      password_confirmation: formData.confirmPassword,
    }
    console.log(formData)

    try {
      const response = await api({
        url: '/user_auth/update_user',
        method: 'PATCh',
        headers: {
            "Content-Type": "application/json",
        },
        data: payload
      })
      alert('Data has been updated')
      saveAuthDetails({
        data: {
          user: response.data.record,
        }
      })
      clearForm()
    } catch (error) {
      const formattedError = error?.response?.data?.message
      if (formattedError) {
        alert(formattedError)
      }
    }
  }

  function clearForm () {
    if (props.formType === 'create') {
      formData.fullName = ''
      formData.email= ''
      formData.departmentId= ''
      formData.password= ''
      formData.confirmPassword= ''
      formData.role_id= ''
      formData.studentNumber = ''
    } else {
      formData.password= ''
      formData.confirmPassword= ''
    }

    pressedSubmit.value = false
  }

</script>

<style lang="scss" scoped>
* {
    box-sizing: border-box;
}

.user-information-form {
  background-color: #CAA7D3;
  border-radius: .5rem;
  margin: 1rem;
  padding: 1.5rem;
  max-width: 98%;
  color: white;

  &__submit-container {
    margin: 20px 0;
  }

  &--user-registration {
    margin: 0;
    padding: 0;
    width: 100%;
    max-width: 100%;
    background-color: transparent;
    .bgCard {
      margin: 0;
      padding: 0;
      background-color: transparent;
      width: 100%;
      max-width: 100%;
    }

    .childFormContainer {
      width: 100%;
      input, select {
        width: 100%;
      }
    }
    .user-information-form__selector-container {
      width: 100%;
      &--disabled {
        opacity: 0.8;
        pointer-events: none;
      }
    }
  }
  &--create-dean {
    // create dean styling here
    background-color: transparent;
  }
}


.user-information-form {
  &__selector-container {
    width: 100%;
  }
}

.user-information-form__form-group {
  margin-bottom: 10px;
}
.formParentContainer {
    display: flex;
    justify-content: center;
    
}
.childFormContainer {
  width: 100%;
  margin: 0 auto;
    
}

label {
  letter-spacing: 1px;
  display: block;
  color: black;
  margin-bottom: 5px;

}

input {
  box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.3);
  border: none;
  border-radius: 0.15rem;
  padding: 0.25rem 0;
  padding-left: 0.5rem;
  margin-bottom: 0.5rem;
  outline-color: rgb(43, 71, 230);
  width: 100%;
}

button {
  width: 100px;
  border: none;
  border-radius: 3px;
  padding-top: 5px;
  padding-bottom: 5px;
  letter-spacing: 1px;
  background-color: #9455a3;
  font-weight: 500;
  color: white;
  &:hover {
    background-color: #874d96;
  }
}


.bgCard-none {
    max-width: 100%;
}

</style>