<template>
  <DashboardCard 
      v-for="(item, index) in dashboardItems" 
      :key="index"
      :item="item"
  />
</template>

<script setup>
const props = defineProps({
    dashboardItems: {
        type: Array,
        default: () => {}
    },
})
</script>

<style lang="scss" scoped>
// CARD PROPERTIES -> GLOBAL CSS
* {
    border: none;
    box-sizing: border-box;
}

//  END
</style>