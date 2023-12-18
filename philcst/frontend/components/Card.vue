<template>
  <div class="flow">
    <div class="mycard">
      <slot />
    </div>
  </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>
$bg-accent: #D7A2F0;

@mixin cardShadow {
  box-shadow: 0px 5px 13px 2px rgba(0, 0, 0, 0.40);
  -webkit-box-shadow: 0px 5px 13px 2px rgba(0, 0, 0, 0.40);
  -moz-box-shadow: 0px 5px 13px 2px rgba(0, 0, 0, 0.40);
}


.flow {
  display: grid;
  place-items: center;
  min-height: 100vh;
}

.mycard {
  @include cardShadow();
  background-color: $bg-accent;
  border-radius: 1.5rem;
  font-family: 'Lucida Sans';
}
</style>