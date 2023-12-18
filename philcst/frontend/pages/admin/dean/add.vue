<template>
  <div>
    <div class="transition">
      <nav>
          <h2>Add Dean</h2>
      </nav>   

      <div class="bgCard">
        <div class="dean-page__info-container">
          <UserInformationForm
            :formType="'create'"
            :role="'dean'"
            :customClass="'create-dean'"
          />
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>
* {
    box-sizing: border-box;
}

.dean-page {
  background-color: #954087;
  border-radius: .5rem;
  margin: 1rem;
  padding: 1.5rem;
  max-width: 100%;
  color: white;

  &__selector-container {
    width: 100%;
  }
  &__info-container {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
  }
  &__form-group {
    margin-bottom: 10px;
  }
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
  display: block;

}

input {
  box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.3);
  border: none;
  border-radius: 0.15rem;
  padding: 0.25rem 0;
  padding-left: 0.5rem;
  margin-bottom: 0.5rem;
  outline-color: rgb(43, 71, 230);
    width: 90%;
}

button {
  width: 100px;
}


</style>