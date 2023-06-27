<template>
  <div class="content-ressource " style="overflow: auto;position: relative;">
    <div class="container slides">
      <v-carousel
        @change="onSlideChange"
        :interval="1000000"
        v-model="currentSlideIndex"
        cycle
        height="100%"
        hide-delimiters
        data-bs-interval="false"
        data-interval="false"
      >
        <!-- :interval="interval" -->
        <v-carousel-item
          v-for="(item, index) in ressource.content"
          :key="index"
        >
          <v-sheet
            height="100%"
            width="100%"
            class="sheet-slider"
            style="background:#fff;color:#000;"
          >
            <div class="main-slider">
              <label
                for="recipient-name"
                class="form-control-label"
                :key="index"
              >
              </label>
              <template>
                <v-sheet
                  v-show="isComplate"
                  class="mx-auto mt-3 sheet-slider"
                  elevation="8"
                  max-width="800"
                  style="background: #e5e5e5;margin: auto;padding: 5px 20px; margin-left: 60px;"
                >
                  <v-slide-group
                    v-model="model"
                    class="pa-4"
                    active-class="success"
                    show-arrows
                    style="flex-direction: column !important;"
                  >
                    <!-- v-slot="{ active, toggle }" -->
                    <v-slide-item
                      v-for="(gloss, y) in item.glossary"
                      :key="y"
                      style="flex-direction: column !important;"
                    >
                      <v-card
                        :color="
                          activeGlossary == y ? undefined : 'grey lighten-1'
                        "
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
                              gloss.image +
                              ')',
                          },
                          activeGlossary != y ? { opacity: '0.5' } : '',
                        ]"
                        @click="toggleGlossary(index, y, gloss)"
                      >
                      </v-card>
                    </v-slide-item>
                  </v-slide-group>
                </v-sheet>
              </template>
              <div
                class="glossary-type mt-2"
                style="width:100%;min-width: 850px;margin: auto;"
              >
                <div class="gloassary-definition">
                  {{ ressource.content[currentSlideIndex].glossaryName }}
                </div>
                <div class="gloassary-name" v-show="isComplate">
                  {{ ressource.content[currentSlideIndex].glossaryDescription }}
                </div>
                <!-- id="kt_user_edit_avatar" -->
                <div
                  v-show="isComplate"
                  class="img"
                  style="height: 75%;width: 50% !important;object-fit: contain;margin: auto !important;"
                >
                  <img
                    style="width: 70%;object-fit: contain;"
                    :src="
                      url_base +
                        '/assets/schools/' +
                        url_base.split('/')[4] +
                        '/lms/lecons_files/' +
                        glossaryItem.image
                    "
                    alt=""
                  />
                </div>
              </div>
            </div>
          </v-sheet>
        </v-carousel-item>
      </v-carousel>
      <div class="magic-brasher">
        <button @click="showCompletion()">
          <img
            width="50px"
            height="50px"
            :src="url_base + '/assets/lms/icons/magic-wand.png'"
            alt=""
          />
        </button>
        <button @click="showCompletion()">
          <img
            width="50px"
            height="50px"
            :src="url_base + '/assets/lms/icons/magic-wand.png'"
            alt=""
          />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ressource", "url_base"],
  data() {
    return {
      activeGlossary: 0,
      currentSlideIndex: 0,
      isComplate: false,
      glossaryItem: { image: this.ressource.content[0].glossary[0].image },
    };
  },
  mounted() {
    let groups = document.getElementsByClassName("v-slide-group__content");
    for (let i = 0; i < groups.length; i++) {
      groups[i].style.flexDirection = "column";
    }
  },
  methods: {
    toggleGlossary(index, y, item) {
      this.activeGlossary = y;
      this.glossaryItem = item;
      // this.activeGlossaryForm = true;
    },
    onSlideChange() {
      setTimeout(() => {
        let groups = document.getElementsByClassName("v-slide-group__content");
        for (let i = 0; i < groups.length; i++) {
          groups[i].style.flexDirection = "column";
        }
      }, 10);
      this.isComplate = false;
      if (this.ressource.content[this.currentSlideIndex].glossary[0]) {
        this.glossaryItem = this.ressource.content[
          this.currentSlideIndex
        ].glossary[0];
      } else {
        this.glossaryItem = { content: "", answer: "", image: "" };
      }
    },
    showCompletion() {
      this.isComplate = true;
    },
  },
};
</script>

<style lang="scss" scoped>
.gloassary-name {
  font-size: 30px;
  margin-bottom: 5px;
  width: 70%;
  margin: auto !important;
  overflow: hidden;
  color: #171656;
}
.gloassary-definition {
  font-size: 15px;
  margin-bottom: 5px;
  width: 100%;
  font-weight: 700;
  text-align: center;
  overflow: hidden;
  color: #171656;
}
.v-slide-group__next i,
.v-slide-group__prev i {
  background: #80808096;
  color: white;
  border-radius: 50%;
}
.glossary-type {
  text-align: center;
}
.main-slider {
  margin: auto;
  max-width: 100%;
  width: 100%;
  text-align: left;
  height: 100%;
  display: flex;
  overflow-y: auto;
  justify-content: flex-start;
  align-items: center;
  flex-direction: row;
}
.v-window__next,
.v-window__prev {
  background: black;
}
.sheet-slider {
  background: rgb(229, 229, 229);
  margin: auto;
  padding: 5px 20px;
  position: absolute;
  top: 55%;
  transform: translateY(-50%);
  height: 50%;
  overflow: hidden;
  overflow-y: auto;
}
.magic-brasher {
  width: 100%;
  display: flex;
  justify-content: space-between;
}
@media (min-width: 1500px) {
  .gloassary-name {
    font-size: 3rem;
    margin: 0;
  }
  .gloassary-definition {
    font-size: 3rem;
  }
}
</style>
