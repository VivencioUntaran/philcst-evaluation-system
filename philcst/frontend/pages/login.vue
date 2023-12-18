
<template>
  <Card>
    <div class="mycard__body">
      <div class="mycard__box column-1">
          <img src="~/assets/logo.png" class="mycard__img" alt="">
      </div>

        <form 
            class="mycard__box column-2"
            @submit.prevent="handleSubmit"
        >
          <p class="mycard__text-detail">Please enter your log-in details below.</p>
          <label for="">Email:</label>
          <input 
            type="email" 
            name="" 
            id=""
            required
            email
            v-model="formData.email"
            @input="errorMessage = ''"
          >

          <label for="">Password:</label>
          <input 
            type="password"
            name="" id=""
            required
            v-model="formData.password"
            @input="errorMessage = ''"
          >

          <p 
            v-if="errorMessage"
            class="login__error-message"
          > {{ errorMessage }} </p>

          <button class=" mycard__button">Submit</button>
          <p class="mycard__text-center"> Are you a student and don’t you have an account? Click <nuxt-link to="/register">here</nuxt-link> to register.</p>
        </form>
    </div>
  </Card>
</template>
  
<script setup>
  const api = useApi()
  const { 
    saveAuthDetails,
    handleRedirection,
    checkIfAuthenticated
  } = useCustomAuth()
  const formData = reactive({
    email: '',
    password: '',
  })
  const errorMessage = ref('')

  async function handleSubmit () {
    try {
      const response = await api({
          url: 'user_auth/login',
          method: 'POST',
          headers: {
              "Content-Type": "application/json",
          },
          data: formData,
      })
      const role = response.data.user.role.role
      saveAuthDetails(response)
      window.open(`/${role}`, '_SELF')
    } catch (error) {
      errorMessage.value = error.response?.data?.message
    }
  }

  onMounted(() => {
    checkIfAuthenticated()
  })
</script>
  
<style lang="scss" scoped>
.login {
  &__error-message {
    color: red;
  }
}

// ||  CARD BODY  ||

@mixin formShadow {
    box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.3);

}

@mixin btnshadow {
    box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.3);
}

.mycard {


    &__body {
        display: flex;
        padding: 2rem;
        gap: 3rem;
        width: 900px;

    }


    &__box {
        display: flex;
        flex-basis: 100%;
        flex-direction: column;

    }

    &__img {
        width: 80%;
        align-self: center;
    }

    &__button {
        @include btnshadow();
        border: none;
        border-radius: .15rem;
        padding: .20rem;
        margin-top: 1rem;
        background-color: rgba(134, 48, 184, 0.801);
        color: white;
    }

    &__note {
        font-style: italic;
    }

    &__redirect {
        align-self: center;
        font-style: italic;
    }

    &__text-center {
        margin-top: 1rem;
        text-align: center;
    }

    .column-1 {
        justify-content: center;
        align-items: center;
    }

    .column-2 {
        margin-top: 2rem;
    }

    input,
    select {
        @include formShadow();
        border: none;
        border-radius: .15rem;
        padding: .25rem 0;
        padding-left: .5rem;
        margin-bottom: .5rem;
        outline-color: rgb(43, 71, 230)
    }

    label {
        margin-bottom: .25rem;
    }

    button:hover {
        background-color: rgb(134, 48, 184);
        outline-color: rgb(112, 187, 248);
        color: white;
    }
}



// || MEDIA QUERIES ||
@media screen and (max-width: 810px) {
    .mycard__body {
        width: 750px;
    }
}

@media screen and (max-width: 760px) {
    .mycard__body {
        width: 700px;
    }
}

@media screen and (max-width: 700px) {
    .mycard__body {
        width: 600px;
    }
}

@media screen and (max-width: 600px) {
    .mycard__body {
        width: 550px;
    }
}

@media screen and (max-width: 550px) {
    .flow {
        min-height: 100svh;
    }

    .mycard {


        &__body {
            flex-wrap: wrap;
            max-width: 450px;
            gap: 1rem;
            padding-bottom: .5rem;
        }

        &__img {
            width: 200px;
        }

        &__link {
            margin-bottom: 0;
        }

        &__text-detail {
            margin-top: .75rem;
            margin-bottom: .5rem;
        }

        .column-2 {
            margin-top: 0;

        }


    }


}
</style>
  