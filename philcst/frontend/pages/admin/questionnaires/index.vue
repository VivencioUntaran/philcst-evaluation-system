<template>
  <div>
    <div>
      <nav>
        <h2>
            Questionnaires
        </h2>
      </nav>

      <div class="bgCard">
        <div 
          v-for="(item, key) in pageContent" 
          :key='key'
          class="stack-container"  
        >
          <div>
            <h5 class="mb-3">{{ item.name}}</h5>
          </div>

          <div>
            <nuxt-link
              class="btn-yellow text-decoration-none link-dark"
              :to="`/admin/questionnaires/${item.slug}`"
            >
              View Criterias
            </nuxt-link>
          </div>
        </div>
      </div>
    </div>
</div>
</template>

<script setup>
    const api = useApi()
    const pageContent = ref({})

    const getData = async () => {
      try {
        const response = await api({
            url: 'admin/questionnaires/',
            method: 'GET',
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        pageContent.value = response.data.records; // Assign the fetched data to the 'items' ref   

      } catch (error) {
          console.error('Error fetching data:', error);
      }
    }

    onMounted(() => {
      getData()
    })

</script>

<style lang="scss" scoped>
.stack-container {
    background-color: #954087;
    border-radius: .5rem;
    margin: 1rem;
    padding: 1.5rem;
    max-width: 98%;
    color: white;
    display: flex;
    justify-content: flex-start;
    flex-direction: column;
}

@media screen and (max-width: 380px) {
  .btn-yellow {
    flex-shrink: 1;

  }
}

</style>