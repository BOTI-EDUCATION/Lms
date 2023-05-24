<template>
  <div>
    <section class="my-courses exercices">
      <div class="pull-left menu-vertical pt-0">
        <div v-slimscroll="optionsScroll">
          <button class="menu-close visible-xs visible-sm">
            <i class="fa fa-times" aria-hidden="true"></i>
          </button>
          <ul class="list-unstyled calcul-mental-menu" id="menu-left">
            <li
              v-for="ressource_item in ressources"
              v-bind:key="ressource_item.id"
            >
              <a
                href="#"
                @click="changeRessource(ressource_item)"
                v-bind:class="{
                  active: ressource && ressource_item.id == ressource.id,
                }"
              >
                <span class="icon">
                  <span>
                    <!-- <img :src="ressource_item.icon" alt /> -->
                    <img
                      src="/lms/assets/lms/icons/online-learning.png"
                      style="width:30px !important;height: 30px !important;"
                    />
                  </span>
                </span>
                <span class="title">{{ ressource_item.label }}</span>
                <span class="pull-right time">
                  <!-- <img :src="'/assets/borne/img/clock.svg'" alt /> -->
                  <img :src="'/lms/assets/lms/icons/fast-time.png'" alt />
                  {{ ressource_item.duree }} min
                </span>

                <span class="chart" data-percent="50"></span>
              </a>
            </li>
            <li class="btn-retour text-center">
              <router-link
                :to="{ name: 'borne_home' }"
                class="btn btn-main text-uppercase"
              >
                <i class="material-icons icon-btn">&#xE317;</i> RETOUR AU
                PROGRAMME
              </router-link>
            </li>
          </ul>
        </div>
      </div>

      <div class="pull-right courses-content">
        <a
          style="color: white;font-size: 30px;display: flex;align-items: center;justify-content: center;"
          href="#"
          ref="fullScrenBtn"
          class="full-screen-btn"
        >
          &larr;
        </a>
        <div v-if="ressource_content" class="logo-game">
          <img :src="ressource.icon" alt />
          {{ ressource.label }}
        </div>
        <div
          v-if="ressource_content"
          class="time-reste"
          v-bind:class="{
            ecoule: timeCountQuestion / 4 / 60 > ressource_content.duree,
          }"
        >
          <img :src="'/assets/borne/img/clock.svg'" alt />
          Temps écoulé :
          <span
            style="min-width: 50px; display: inline-block; text-align: center"
            >{{ timer_val }}</span
          >
        </div>

        <div
          v-if="ressource && !ressource_content"
          class="calcul-mental-commencer text-center bloc-center"
        >
          <div class="col-md-8 col-md-offset-2">
            <img :src="ressource.icon" alt />
            <h2>{{ ressource.label }}</h2>
            <p>{{ ressource.introduction }}</p>
            <div>
              <button
                @click="changeRessourceContent(0)"
                class="btn btn-main text-uppercase"
              >
                COMMENCER
              </button>
              <button
                style="margin-top: 10px; margin-left: 5px"
                type="button"
                class="btn btn-primary btn-lg"
                @click="showContents()"
                data-toggle="modal"
                data-target="#myModal"
              >
                <i class="fa fa-info-circle" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>

        <div
          class="game-pdf-image"
          v-if="
            ressource_content &&
              ressource_content.files &&
              ressource_content.type_id == 3
          "
        >
          <img :src="file_content" />
        </div>

        <div
          v-if="ressource_content"
          class="game text-center mt-70"
          v-bind:class="{
            'bloc-center': !ressource_content,
            'bloc-content': ressource_content,
          }"
        >
          <div
            v-if="ressource_content.type_id == 4"
            class="fluidMedia ressource-content"
          >
            <iframe
              width="560"
              height="315"
              :src="ressource_content.link.replace('watch?v=', 'embed/')"
              :title="ressource_content.content"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>

          <div v-if="ressource_content.type_id == 5">
            <div
              class="fluidMedia ressource-content"
              v-html="ressource_content.content"
            ></div>
            <div v-html="ressource_content.link"></div>
          </div>

          <div
            v-if="ressource_content.type_id == 6"
            style="margin: 0 auto;max-width: 80%;text-align: left;"
          >
            <div
              v-if="ressource_content.type_id == 6"
              v-html="ressource_content.content"
            ></div>
          </div>

          <div
            v-if="ressource_content.type_id == 7"
            style="max-width: 50%;margin: 0 auto;"
          >
            <v-carousel
              cycle
              height="400"
              hide-delimiter-background
              show-arrows-on-hover
            >
              <v-carousel-item
                v-for="(slide, i) in ressource_content.content"
                :key="i"
              >
                <v-sheet
                  :dark="false"
                  :color="colors[i]"
                  height="100%"
                  style="background:#fff;color:#000"
                >
                  <div
                    style="margin: 0 auto;max-width: 80%;text-align: left;margin-top:16p;margin-bottom:16px"
                  >
                    <div class="text-h2" v-html="slide.content"></div>
                  </div>
                </v-sheet>
              </v-carousel-item>
            </v-carousel>
            <div class="clear-fix"></div>
          </div>

          <div
            class="col-md-10 col-md-offset-1"
            v-if="ressource_content.type_id != 4"
          >
            <h3 class="text-center" v-if="ressource_content.type_id == 1">
              {{ ressource_content.content }}
            </h3>

            <div
              v-if="ressource_content.type_id == 2"
              v-html="ressource_content.content"
            ></div>
            <div class="clear-fix"></div>
          </div>
        </div>

        <div
          class="game-pdf-actions"
          v-if="
            ressource_content &&
              ressource_content.files &&
              ressource_content.type_id == 3
          "
        >
          <a href="#" @click="changeFileContent('prev')">Précédent</a>
          <a href="#" @click="changeFileContent('next')">Suivant</a>
        </div>

        <a
          href="#"
          @click="changeRessourceContent(ressource_content_index + 1)"
          v-if="
            ressource_content &&
              ressource.contents.length > ressource_content_index + 1
          "
          class="btn btn-main text-uppercase btn-next btn-pdf"
        >
          Continuer
          <i class="material-icons icon-btn rotate">&#xE317;</i>
        </a>

        <div
          v-if="ressources.length > 0 && !ressource && !ressource_content"
          class="calcul-mental-commencer text-center bloc-center"
        >
          <div class="col-md-8 col-md-offset-2">
            <router-link
              :to="{ name: 'borne_home' }"
              class="btn btn-main text-uppercase"
            >
              <i class="material-icons icon-btn">&#xE317;</i> RETOUR AU
              PROGRAMME
            </router-link>
          </div>
        </div>
        <a
          href="#"
          @click="changeRessource()"
          v-if="
            ressource &&
              ressource.contents.length == ressource_content_index + 1
          "
          class="btn btn-main text-uppercase btn-next btn-pdf"
        >
          ETAPE SUIVANTE
          <i class="material-icons icon-btn rotate">&#xE317;</i>
        </a>
      </div>
    </section>
    <div
      class="modal fade"
      id="myModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h4
              class="modal-title"
              v-if="ressource"
              style="color: #000"
              id="myModalLabel"
            >
              {{ ressource.label }}
            </h4>
          </div>
          <div class="modal-body" style="padding: 25px 50px">
            <ul class="lecon-tasks" style="position: relative">
              <li
                class="two green"
                v-for="content_item in contents"
                v-bind:key="content_item.id"
              >
                <span class="task-title">{{ content_item.type_label }}</span>
                <span class="task-time">{{ content_item.duree }}min</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import carousel from "vue-owl-carousel";

export default {
  components: { carousel },
  data() {
    return {
      optionsScroll: {
        height: "calc(100vh)",
        allowPageScroll: true,
        size: "5px",
        start: "bottom",
      },
      url:
        "borne_lecon/" + (this.$route.params.id ? this.$route.params.id : ""),
      niveaux: [],
      unites: [],
      rubriques_tree: [],
      filter: {
        niveau_id: null,
        unite_id: null,
        code: null,
      },
      filter_title: {
        niveau_label: null,
        unite_label: null,
      },
      lecon: null,
      ressource: null,
      contents: [],
      ressource_content: null,
      ressource_content_index: null,
      ressources: [],
      intIdTimer: null,
      timeCountQuestion: null,
      timer_val: null,
      file_content: null,
      file_content_index: null,
      colors: [
        "indigo",
        "warning",
        "pink darken-2",
        "red lighten-1",
        "deep-purple accent-4",
      ],
    };
  },
  metaInfo() {
    return {
      title: `${(this.salon_name ? this.salon_name + " :" : "") +
        " Téléchargeable sur iPhone, iPad ou sur les appareils Android"}`,
    };
  },
  created() {
    console.log(" Home LMSSS");
    this.fetch();
  },
  mounted() {
    console.log("Component mounted......");
    LMSScriptHeader.init();
    LMSScript.init();
  },
  methods: {
    fetch() {
      let params = {};
      if (this.$route.params.rcode) {
        this.url = "borne_rcode/" + this.$route.params.rcode;
      }

      axios
        .get(this.url, {
          params: params,
        })
        .then(
          function(response) {
            this.lecon = response.data.lecon;
            this.ressources = response.data.ressources;
            if (this.ressources) {
              this.ressource = this.ressources[0];
            }
            setTimeout(
              function() {
                LMSScript.init();
                this.$refs.fullScrenBtn.click();
              }.bind(this),
              100
            );
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
    changeUnite(unite) {
      this.filter.unite_id = unite.value;
      this.$refs.uniteTrigger.click();
      this.fetch();
    },
    changeNiveau(niveau) {
      this.filter.niveau_id = niveau.value;
      this.$refs.niveauTrigger.click();
      this.fetch();
    },
    clearTimer() {
      this.timer_val = null;
      this.timeCountQuestion = 0;
      clearInterval(this.intIdTimer);
    },
    changeRessource(ressource = null) {
      this.ressource_content = null;
      this.ressource_content_index = null;
      this.clearTimer();
      if (!ressource && this.ressource) {
        let resourcesFilter = this.ressources.filter(
          (c) => c.ordre > this.ressource.ordre
        );
        console.log("resources", resourcesFilter);
        if (resourcesFilter.length > 0) {
          console.log("resources[0]", resourcesFilter[0]);
          ressource = resourcesFilter[0];
        }
      }
      this.ressource = ressource;
    },
    changeRessourceContent(index) {
      this.ressource_content_index = index;
      if (
        this.ressource &&
        this.ressource.contents[this.ressource_content_index]
      ) {
        this.clearTimer();
        this.ressource_content = this.ressource.contents[
          this.ressource_content_index
        ];
        if (this.ressource_content && this.ressource_content.files) {
          this.file_content = this.ressource_content.files[0];
          this.file_content_index = 0;
        }
        this.intIdTimer = setInterval(this.timer, 250);
      }

      setTimeout(function() {
        LMSScript.init();
      }, 100);
    },
    showContents() {
      this.contents = this.ressource.contents;
      console.log("this.contents", this.contents);
    },
    changeFileContent(action) {
      if (action == "prev") {
        if (this.ressource_content.files[this.file_content_index - 1]) {
          this.file_content = this.ressource_content.files[
            this.file_content_index - 1
          ];
          this.file_content_index = this.file_content_index - 1;
        }
      }
      if (action == "next") {
        if (this.ressource_content.files[this.file_content_index + 1]) {
          this.file_content = this.ressource_content.files[
            this.file_content_index + 1
          ];
          this.file_content_index = this.file_content_index + 1;
        }
      }
    },
    timer() {
      var secs = Math.floor(this.timeCountQuestion / 4) % 60;
      var mins = Math.floor(this.timeCountQuestion / 240);
      this.timer_val = mins + ":" + formatTime(secs);
      this.timeCountQuestion++;
    },
  },
};
</script>
<style>
.v-window__next {
  position: absolute !important;
  right: 0px !important;
}
</style>
