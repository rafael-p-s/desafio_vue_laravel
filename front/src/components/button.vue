<template>
  <div>
    <button :class="btnStyle" @click="onClick">{{ btnText }}</button>
  </div>
</template>

<script setup>
const props = defineProps({
  btnText: String,
  btnStyle: String,
});

const emit = defineEmits(["click"]);

function onClick(e) {
  emit("click"); // está informando o pai
}
</script>

<style scoped>

</style>
