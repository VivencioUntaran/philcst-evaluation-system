<template>
  <div>
      <div>
          <nav>
              <h2>
                {{ $route.params.reportSlug }}
              </h2>
          </nav>
      </div>
  </div>
</template>
  
<script setup>

</script>

<style lang="scss" scoped>
input {
  display: block;
}

.hideBtn {
  display: none;
}

.stack-container {
  background-color: #954087;
  border-radius: .5rem;
  margin: 1rem;
  padding: .5rem;
  max-width: 98%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.delBtn {
  margin-left: 5px;
}



</style>

