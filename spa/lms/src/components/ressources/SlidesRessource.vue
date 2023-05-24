<template>
  <div class="content-ressource slides">
    <v-carousel
      cycle
      height="100%"
      :interval="1000000"
      hide-delimiters
      show-arrows-on-hover
    >
      <v-carousel-item v-for="(slide, i) in content" :key="i" cover>
        <v-sheet
          :color="colors[i]"
          height="100%"
          style="background:#fff;color:#000"
        >
          <div
            style="margin: 0 auto;max-width: 80%;text-align: left;margin-top:16p;margin-bottom:16px"
          >
            <div v-html="slide.content"></div>
          </div>
        </v-sheet>
      </v-carousel-item>
    </v-carousel>
    <div class="clear-fix"></div>
  </div>
</template>

<script>
export default {
  props: ["content"],
  data() {
    return {
      colors: [
        "indigo",
        "warning",
        "pink darken-2",
        "red lighten-1",
        "deep-purple accent-4",
      ],
    };
  },
};
</script>

<style lang="scss">
@media (min-width: 1500px) {
  .slides {
    font-size: 2rem;
  }
}
</style>
