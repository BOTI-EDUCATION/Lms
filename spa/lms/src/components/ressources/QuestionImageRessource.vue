<template>
  <div
    class="content-ressource"
    style="position: relative;overflow: auto;"
    :style="zoomOnImage ? 'z-index: 100;' : ''"
  >
    <div class="container" style="position: relative;">
      <div class="wrapper-ress">
        <div class="question" v-if="ressource.content">
          {{ ressource.content }}
        </div>
        <div
          class="img"
          style="width:100%;cursor: zoom-in;"
          @click="zoomImage()"
          :style="ressource.content ? 'height:70%' : 'height:100%'"
        >
          <img
            :src="
              url_base +
                '/assets/schools/' +
                url_base.split('/')[4] +
                '/lms/lecons_files/' +
                ressource.image
            "
            alt=""
          />
        </div>
      </div>
    </div>
    <div class="img-zoom" v-show="zoomOnImage" @click="zoomImageOut()">
      <img
        :src="
          url_base +
            '/assets/schools/' +
            url_base.split('/')[4] +
            '/lms/lecons_files/' +
            ressource.image +
            '?v=12354'
        "
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
  data() {
    return {
      zoomOnImage: false,
    };
  },

  methods: {
    zoomImage() {
      this.zoomOnImage = true;
    },
    zoomImageOut() {
      this.zoomOnImage = false;
    },
  },
};
</script>

<style scoped>
.img {
  margin: 0;
}
.img img {
  object-fit: contain !important;
}
</style>
