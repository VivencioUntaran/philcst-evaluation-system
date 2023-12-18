<template>
    <div :class="['box',item.cardColor]">
        <div class="firstBox">
            <img 
              :src="item.image.src"
              class="dashboard-card__image"
            />
        </div>
        <div class="secondBox">
            <h3>{{ item.text }}</h3>
            <h4 v-if="item.acadYear">{{ item.acadYear }} {{ item.semester }}</h4>
            <h5 v-if="item.evalStatus">{{ item.evalStatus }}</h5>
            <NuxtLink
              v-if="item.btn && item.btn.show"
              :to="item.btn.link"
              :class="['evaluateButton', item.btn.evalStatus === 'closed' && 'evaluateButton--disabled']"
            >
              {{ item.btn.label }}
            </NuxtLink>

            <div v-if="item.btn">
              <span v-if="item.btn.evalStatus === 'closed'">
                You can start evaluating once the status becomes open.
              </span>
            </div>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps({
        item: {
            type: Object,
            default: () => {},
        },
    })
</script>

<style lang="scss" scoped>
* {
    border: none;
    text-decoration: none;
}
.box {
    display: flex;
    justify-content: center;
    align-items: center;
}
.firstBox {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}
.secondBox {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}


.blue-box {
    background-color: #2ACEE4;
    border-radius: 1rem;
    box-shadow: 0px 2px 5px 1px rgba(0, 0, 0, 0.40);
    margin-bottom: 1.5rem;
}

.yellow-box {
    background-color: #e3a253;
    border-radius: 1rem;
    box-shadow: 0px 2px 5px 1px rgba(0, 0, 0, 0.40);
}

img {
    max-width: 90%;
}
h4 {
    font-style: italic
}

.evaluateButton {
    background-color: #a54364;
    border: none;
    color: white;
    border-radius: 1rem;
    margin-left: .5rem;
    padding: .25rem 1rem;
    margin-bottom: 1rem;
  
    &--disabled {
      opacity: 0.5;
      pointer-events: none;
    }

  &:hover {
    background-color: #a85c76;
    outline-color: #70bbf8;
  }
}

.dashboard-card {
    &__image {
        width: 240px;
    }
}

@media screen and (max-width: 620px) {
    .box {
        flex-direction: column;
        flex-wrap: wrap;
    }
}
</style>