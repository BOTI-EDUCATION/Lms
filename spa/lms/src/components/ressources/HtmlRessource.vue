<template>
  <div
    style="color: black;justify-content: center;"
    class="content-ressource content-2"
  >
    <div
      class="html"
      style="overflow-y: auto;width: 100%;text-align: center;"
      v-html="content"
    ></div>
  </div>
</template>

<script>
export default {
  props: ["content"],
};
</script>

<style></style>
