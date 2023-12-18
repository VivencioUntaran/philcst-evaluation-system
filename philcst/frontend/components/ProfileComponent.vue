<template>
  <div class="profile-component">
    <h1> Edit Profile </h1>

    <div class="profile-component__info-container">
      <UserInformationForm 
        :role="props.role"
        :formType="'update'"
      />
    </div>
  </div>
</template>

<script setup>
  const props = defineProps({
    role: {
      type: String,
      default: '', // admin, student, dean
    }
  })
</script>