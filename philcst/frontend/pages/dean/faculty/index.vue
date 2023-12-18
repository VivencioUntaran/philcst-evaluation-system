<template>
  <div>
    <FacultyInstructorTable 
      :addButton="{ label: 'Add Faculty', url: '/dean/faculty/add' }"
      :backButton="{ label: 'Back to Faculties', url: '/dean/faculty' }"
      :title="'Faculty'"
      :role="'faculty'"
    />
  </div>
</template>