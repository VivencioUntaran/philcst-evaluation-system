<!-- || VERIFY PAGE|| -->
<template>
  <!-- <Card>
    <div class="mycard__body">
      <img src="~/assets/verify.svg" class="mycard__img" alt="">
      <h2>Verify Email</h2>
      <p>Kindly provide your email address so we can send a link for your account creation.</p>
      <div class="mycard__form">
        <input type="email" name="" id="">
        <button>Submit</button>
      </div>
      <p>Already have an account? Click here to log-in.</p>
    </div>
  </Card> -->
</template>

<script setup>
  onMounted(() => {
    const router = useRouter()
    router.push('/login')
  })
</script>

<style lang="scss" scoped>
.mycard {

  &__body {
    display: grid;
    place-items: center;
    width: 800px;
    text-align: center;

  }

  &__img {
    width: 50%;
  }

  input {
    border: none;
    border-radius: .15rem;
    padding: .25rem 0;
    padding-left: .5rem;
    margin-bottom: .5rem;
    outline-color: rgb(43, 71, 230)
  }

  button {
    background-color: rgba(134, 48, 184, 0.801);
    border: none;
    color: white;
    border-radius: .15rem;
    margin-left: .5rem;
    padding: .25rem 1rem;

  }

  button:hover {
    background-color: rgb(129, 32, 185);
    outline-color: rgb(112, 187, 248);
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
    width: 500px;
  }
}

@media screen and (max-width: 600px) {
  .mycard__body {
    width: 500px;
  }
}

@media screen and (max-width: 500px) {
  .mycard__body {
    width: 450px;
  }
}
</style>
