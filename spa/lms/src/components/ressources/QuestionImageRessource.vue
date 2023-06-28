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
              ressource.image
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

      <img
        v-if="false"
        id="testing"
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
.img {
  margin: 0;
}
.img img {
  object-fit: contain !important;
}
</style>
