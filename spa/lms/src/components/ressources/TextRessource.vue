<template>
  <div
    class="content-ressource"
    style="position: relative;overflow: auto;"
    :style="zoomOnImage ? 'z-index: 100;' : ''"
  >
    <div
      class="img"
      style="height: 100%;"
      v-if="
        ressource.content &&
          ressource.content.images &&
          ressource.content.images[activeImage]
      "
    >
      <img
        :src="
          url_base +
            '/assets/schools/' +
            url_base.split('/')[4] +
            '/lms/lecons_files/' +
            ressource.content.images[activeImage] +
            '?v=12354'
        "
        @click="zoomImage()"
        style="width: 100% !;object-fit: contain;height: 80%;"
        alt=""
      />
      <template>
        <v-sheet class="sheet-slider slider-hero" elevation="8">
          <v-slide-group
            v-model="model"
            class="pa-4"
            active-class="success"
            show-arrows
            style="overflow: auto;padding: 5px;box-shadow: 0px 0px 3px 1px #948f8f2e;border-radius: 6px;"
          >
            <!-- v-slot="{ active, toggle }" -->
            <v-slide-item
              v-for="(image, index) in ressource.content.images"
              :key="index"
              style="flex-direction: column !important;"
            >
              <v-card
                :color="activeImage == index ? undefined : 'grey lighten-1'"
                class="ma-5"
                height="100"
                width="100"
                style="background-size: cover;background-position: center;position: relative;margin-right: 10px;"
                v-bind:style="[
                  {
                    'background-image':
                      'url(' +
                      url_base +
                      '/assets/schools/' +
                      url_base.split('/')[4] +
                      '/lms/lecons_files/' +
                      image +
                      ')',
                  },
                  activeImage != index ? { opacity: '0.5' } : '',
                ]"
                @click="toggleImageText(index)"
              >
              </v-card>
            </v-slide-item>
          </v-slide-group>
        </v-sheet>
      </template>
    </div>

    <!-- style="text-align: left !important;width: 60%;" -->
    <textarea
      v-if="ressource.content && ressource.content.text"
      v-bind:style="[
        ressource.content.images.length > 0
          ? ''
          : { 'text-align': 'center !important' },
        ressource.content.images.length > 0 ? '' : { width: '100%' },
      ]"
      id="textarea"
      readonly
      v-html="
        ressource.content.text
          .replace('<p>', '')
          .replace('</p>', '')
          .replace('</br>', '')
          .replace('<strong>', '')
          .replace('</strong>', '')
      "
    ></textarea>
    <div
      class="img-zoom"
      style="cursor: initial;display: flex;justify-content: flex-start;flex-direction: column;height: 100%;"
      v-show="zoomOnImage"
      @dblclick="zoomImageOut()"
    >
      <div style="width: 100%;height: 90%;overflow: hidden;position: relative;">
        <canvas
          id="drawing-board"
          style="background-position: center;z-index: 1254444;"
        ></canvas>
        <img
          style="position: absolute;z-index: -1;top: 0;left: 50%;transform: translateX(-50%);"
          :src="
            url_base +
              '/assets/schools/' +
              url_base.split('/')[4] +
              '/lms/lecons_files/' +
              ressource.content.images[activeImage] +
              '?v=12354'
          "
          alt=""
        />
      </div>
      <div style="margin-top: 5px;display: flex;align-items: center;">
        <button
          id="clear"
          class="menuLevel choice"
          style="border-color: transparent !important;outline: none;"
        >
          Clear
        </button>
        <label for="stroke" style="margin-bottom: 0;">Color</label>
        <input id="stroke" name="stroke" type="color" />
      </div>
      <div id="toolbar" style="display: none;">
        <h1>Draw.</h1>
        <label for="stroke">Stroke</label>
        <input id="stroke" name="stroke" type="color" />
        <label for="lineWidth">Line Width</label>
        <input id="lineWidth" name="lineWidth" type="number" value="5" />
        <button id="clear">Clear</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
  data() {
    return {
      textImage:
        this.ressource.content.images && this.ressource.content.images[0]
          ? this.ressource.content.images[0]
          : null,
      activeImage: 0,
      zoomOnImage: false,
    };
  },
  created() {
    console.log(this.ressource);
  },
  methods: {
    toggleImageText(index) {
      this.textImage = this.ressource.content.images[index];
      this.activeImage = index;
    },
    zoomImage() {
      this.zoomOnImage = true;
      // this.url_base +
      // "/assets/schools/" +
      // this.url_base.split("/")[4] +
      // "/lms/lecons_files/" +
      // this.ressource.image +
      // "?v=12354";

      this.drawOnImage();
    },
    zoomImageOut() {
      this.zoomOnImage = false;
    },
    drawOnImage() {
      const canvas = document.getElementById("drawing-board");
      const clear = document.getElementById("clear");
      const stroke = document.getElementById("stroke");
      const toolbar = document.getElementById("toolbar");
      const ctx = canvas.getContext("2d");

      const canvasOffsetX = canvas.offsetLeft;
      const canvasOffsetY = canvas.offsetTop;

      canvas.width = window.innerWidth - canvasOffsetX;
      canvas.height = window.innerHeight - canvasOffsetY;

      let isPainting = false;
      let lineWidth = 5;
      let startX;
      let startY;

      clear.addEventListener("click", (e) => {
        if (e.target.id === "clear") {
          ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
      });

      stroke.addEventListener("change", (e) => {
        if (e.target.id === "stroke") {
          ctx.strokeStyle = e.target.value;
        }

        if (e.target.id === "lineWidth") {
          lineWidth = e.target.value;
        }
      });

      const draw = (e) => {
        if (!isPainting) {
          return;
        }

        ctx.lineWidth = lineWidth;
        ctx.lineCap = "round";

        // ctx.lineTo(e.clientX - canvasOffsetX, e.clientY - canvasOffsetY);
        // ctx.lineTo(e.clientX + canvasOffsetX, e.clientY + canvasOffsetY);
        ctx.lineTo(e.offsetX + canvasOffsetX, e.offsetY + canvasOffsetY);
        console.log(e.offsetX, e.offsetY);
        ctx.stroke();
      };

      canvas.addEventListener("pointerdown", (e) => {
        console.log(e);
        isPainting = true;
        startX = e.offsetX;
        startY = e.offsetY;
      });
      // canvas.addEventListener("mousedown", (e) => {
      //   isPainting = true;
      //   startX = e.clientX;
      //   startY = e.clientY;
      // });

      canvas.addEventListener("pointerup", (e) => {
        isPainting = false;
        ctx.stroke();
        ctx.beginPath();
      });

      canvas.addEventListener("pointermove", draw);

      const sizeElement = document.querySelector("#sizeRange");
      let size = sizeElement.value;
      sizeElement.oninput = (e) => {
        size = e.target.value;
      };

      const colorElement = document.getElementsByName("colorRadio");
      let color;
      colorElement.forEach((c) => {
        if (c.checked) color = c.value;
      });

      colorElement.forEach((c) => {
        c.onclick = () => {
          color = c.value;
        };
      });
    },
  },
};
</script>

<style scoped>
#textarea {
  color: black;
  width: 60%;
  border: none;
  background-color: white;
  resize: none;
  font-size: 1.7;
}
.slider-hero {
  max-width: 400px;
  background: rgb(229, 229, 229);
  padding: 5px 20px;
  overflow-x: auto;
  margin: 0 auto;
}
</style>
