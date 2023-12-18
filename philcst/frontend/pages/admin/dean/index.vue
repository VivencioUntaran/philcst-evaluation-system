<template>
  <div>
    <FacultyInstructorTable 
      :addButton="{ label: 'Add Dean', url: '/admin/dean/add' }"
      :backButton="{ label: 'Back to Deans', url: '/admin/dean' }"
      :title="'Deans'"
      :role="'dean'"
    />
  </div>
</template>