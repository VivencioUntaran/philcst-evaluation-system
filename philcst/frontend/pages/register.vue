
<template>
  <Card>
    <div class="student-registration__body">
      <div class="student-registration__box">
        <h3 class="mb-3">Student Registration</h3>
        <div class="student-registration__container">
          <UserInformationForm
            :customClass="'user-registration'"
            :role="'student'"
          />
        </div>
        <p class="student-registration__redirect">Already have an account? Click <nuxt-link to="/login">here</nuxt-link> to log-in.</p>
      </div>
    </div>
  </Card>
</template>

<script setup>
  const { getCurrentUser, checkIfAuthenticated } = useCustomAuth()
  onMounted(() => {
    const user = getCurrentUser()
    if (user) {
      checkIfAuthenticated()
    }
  })
</script>

<style lang="scss" scoped>
// ||  CARD BODY  ||

@mixin formShadow {
    box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.3);

}

@mixin btnshadow {
    box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.3);
}

.student-registration {
    &__container {
      margin: 0 0;
    }
    &__body {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        gap: 3rem;
        width: 100%;
        max-width: 900px;
    }


    &__box {
        display: flex;
        flex-basis: 100%;
        flex-direction: column;
        padding: 10px;
    }

    &__img {
        margin-bottom: 1.5rem;
        width: 70%;
    }

    &__button {
        @include btnshadow();
        border: none;
        border-radius: .15rem;
        padding: .20rem;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
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

    p {
        font-size: .90rem
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
        font-weight: 500;
    }

    button:hover {
        background-color: rgb(134, 48, 184);
        outline-color: rgb(112, 187, 248);
        color: white;
    }
}
</style>
  