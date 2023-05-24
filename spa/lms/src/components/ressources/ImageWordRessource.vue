<template>
  <div
    class="content-ressource content-image-word"
    style="position: relative;overflow: auto;"
    :style="zoomOnImage ? 'z-index: 100;' : ''"
  >
    <div
      class="img col-5"
      style="height: 100%;cursor: zoom-in;"
      @click="zoomImage()"
    >
      <img
        :src="
          url_base +
            '/assets/schools/' +
            url_base.split('/')[4] +
            '/lms/lecons_files/' +
            ressource.image +
            '?v=12354'
        "
        style="width: 100% !important;object-fit: contain;"
        alt=""
      />
    </div>
    <div class="col-5 d-flex justify-center align-center word-ressource">
      <span>
        {{ ressource.content }}
      </span>
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
.img-zoom {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffffff85;
  cursor: zoom-out;
}
.img-zoom img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}
</style>
