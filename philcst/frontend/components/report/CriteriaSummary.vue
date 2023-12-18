
<template>
  <div class="stack-container criteria-summary">
    <client-only>
      <Vue3SimpleHtml2pdf
        :ref="exportableRef"
        :options="pdfOptions"
        :filename="exportFilename"
      >
        <div class="criteria-summary__export-area">
          <!-- <div class="criteria-summary__evaluatee-info">
            <div class="mb-2"><b> Faculty: </b> {{ reportData.evaluatee.name }}</div>
            <div class="mb-2"><b> Department: </b> {{ reportData.evaluatee.department.department }}</div>
            <div class="mb-2"><b> Academic Year: </b> {{ `${currentAcademicYear.year} - ${currentAcademicYear.semester} semester` }}</div>
          </div> -->
          <div class="criteria-summary__table-container">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="text-center"> Criteria </th>
                  <th class="text-center"> Average Point </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in reportData.criterias"
                  :key="index"
                >
                  <td > {{ item.criteria.name }} </td>
                  <td class="text-center"> {{ item.average }} </td>
                </tr>
                <tr v-if="reportData.slug === 'peer-to-peer'">
                  <td> Additional Points as asset to the school </td>
                  <td class="text-center"> {{ reportData.peerPoints }} </td>
                </tr>
                <tr>
                  <td> Total Points Earned: </td>
                  <td class="text-center"> {{ reportData.total_points }} </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </Vue3SimpleHtml2pdf>
    </client-only>

    <div class="criteria-summary__export d-flex align-items-center justify-content-center">
      <button
          @click="handleExport"
          :class="['criteria-summary__export-button', isExporting && 'criteria-summary__export-button--disabled']"
          v-if="!isExporting"
        > {{ !isExporting ? 'Print' : 'Printing...' }} </button>
    </div>

    <div 
      class="criteria-summary__comments"
      v-if="reportData.comments.length"
    >
      <h2> Comments {{ reportData.comments.length }} </h2>
      <div
        v-for="(item, index) in reportData.comments"
        class="comment-container"
        :key="index"
      >
        <div> 
          {{ index + 1 }}. {{ item.evaluator.name }}
          <small v-if="item.evaluator.department"> - ({{ item.evaluator.department.department }})</small> 
        </div>
        <p> --- {{ item.comments }} </p>
      </div>
    </div>
  </div>
</template>

<script>
  // options api
  export default {
    props: {
      reportData: {
        type: Object,
        default: () => {},
      },
      currentAcademicYear: {
        type: Object,
        default: () => {},
      },
    },
    data: () => ({
      isExporting: false,
      exportFilename: "my-custom-file.pdf",
      pdfOptions: {
        margin: 15,
        image: {
          type: "jpeg",
          quality: 1,
        },
        html2canvas: { scale: 3 },
        jsPDF: {
          unit: "mm",
          format: "a4",
          orientation: "p",
        }
      },
      exportableRef: 'exportableArea',
    }),
    methods: {
      handleExport () {
        // this.isExporting = true
        // const element = this.$refs[this.exportableRef]
        // element.download()
        // console.log(element)
        this.isExporting = true
        this.toggleElements(true)
        setTimeout(() => {
          window.print()
        }, 800)
        setTimeout(() => {
          this.isExporting = false
          this.toggleElements(false)
        }, 5000)
      },
      toggleElements (hide) {
        // refactored manipulation of elements
        const sidebar = document.querySelector('#sidebar-component')
        if (sidebar) {
          if (hide) {
            sidebar.classList.add('sidebar--hidden')
          } else {
            sidebar.classList.remove('sidebar--hidden')
          }
        }

        const reportTitle = document.querySelector('.report-page__title')
        if (reportTitle) {
          if (hide) {
            reportTitle.classList.add('report-page__title--hidden')
          } else {
            reportTitle.classList.remove('report-page__title--hidden')
          }
        }

        const reportComponent = document.querySelector('#faculty-report-component')
        if (reportComponent) {
          if (hide) {
            reportComponent.classList.add('faculty-report--exporting')
          } else {
            reportComponent.classList.remove('faculty-report--exporting')
          }
        }
      },
    },
  }

  // composition api

  // import Vue3SimpleHtml2pdf from "vue3-simple-html2pdf"
  // defineComponent({
  //   Vue3SimpleHtml2pdf,
  // })
  // const props = defineProps({
  //   reportData: {
  //     type: Object,
  //     default: () => {},
  //   },
  //   currentAcademicYear: {
  //     type: Object,
  //     default: () => {},
  //   },
  // })

  // // export logic
  // const isExporting = ref(false)
  // const exportFilename = "my-custom-file.pdf"

  // const exportableRef = 'exportableArea'
  // const instance = getCurrentInstance()

  // function handleExport () {
  //   isExporting.value = true
  //   const element = instance.refs[exportableRef]
  //   element.download()
  //   isExporting.value = false
  // }

  // const pdfOptions = computed(() => {
  //   return {
  //     margin: 15,
  //     image: {
  //       type: "jpeg",
  //       quality: 1,
  //     },
  //     html2canvas: { scale: 3 },
  //     jsPDF: {
  //       unit: "mm",
  //       format: "a4",
  //       orientation: "p",
  //     }
  //   }
  // })

</script>

<style lang="scss" scoped>

.criteria-summary {
  &__export-button {
    padding: 10px 20px;
    border: 1px solid #fff;
    background-color: #fff;
    color: #954087;
    border-radius: 5px;
    cursor: pointer;
    &--disabled {
      opacity: 0.5;
      pointer-events: none;
    }
  }
}

.stack-container {
  background-color: #954087;
  border-radius: .5rem;
  margin: 5px;
  padding: .5rem;
  max-width: 98%;
  color: rgb(248, 248, 248);
  letter-spacing: 1px;
}

.comment-container {
  background-color: #fcfcfc;
  color: #303030;
  padding: 5px;
  border-radius: 3px;
}

th, td {
  padding: 5px
}



</style>