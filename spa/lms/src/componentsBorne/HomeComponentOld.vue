<template>
  <div>
    <header class="header">
      <div class="header-content">
        <div class="pull-left" style="margin-top: -1px; display: flex">
          <button type="button" class="menu-courses visible-xs visible-sm">
            <i class="fa fa-bars" aria-hidden="true"></i>
          </button>
          <ul class="list-inline menu-item">
            <li v-for="unite in unites" v-bind:key="unite.value">
              <a href="#" @click="changeUnite(unite)">
                <h3 class="text-uppercase">
                  {{ unite.text }}
                  <span></span>
                </h3>
              </a>
            </li>
          </ul>
          <div class="menu-contents">
            <button class="slide-element" ref="uniteTrigger">
              <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
          </div>
          <h3 class="text-uppercase current-matiere text-bg">
            {{
              filter_title.unite_label
                ? filter_title.unite_label
                : "Choisissez une unité"
            }}
            <span></span>
          </h3>
          <a href="#" class="current-class" style="width: max-content">
            <h3 class="text-uppercase">
              Classe :
              {{ filter_title.niveau_label ? filter_title.niveau_label : "-" }}
              <i class="fa fa-angle-right" aria-hidden="true"></i>
            </h3>
          </a>
          <ul
            class="class-menu list-inline"
            style="display: flex; width: max-content"
          >
            <li v-for="niveau in niveaux" v-bind:key="niveau.value">
              <a href="#" @click="changeNiveau(niveau)">{{ niveau.text }}</a>
            </li>
            <li class>
              <a href="#" class="close-menu">
                <i
                  class="fa fa-angle-left"
                  ref="niveauTrigger"
                  aria-hidden="true"
                ></i>
              </a>
            </li>
          </ul>
        </div>
        <div class="pull-right">
          <div class="user">
            <img src="http://placehold.it/50" class="img-circle" alt />
            <span class="hidden-xs">Semoud Ahmed</span>
            <div class="dropdown" style="display: inline">
              <button
                class="btn btn-primary dropdown-toggle"
                type="button"
                data-toggle="dropdown"
              >
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a href="#" class="text-uppercase">Mon profil</a>
                </li>
                <li>
                  <a href="#" class="text-uppercase">DECONNEXIONN</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>
    <section class="my-courses">
      <div class="pull-left menu-vertical">
        <div v-slimscroll="optionsScroll">
          <button class="menu-close visible-xs visible-sm">
            <i class="fa fa-times" aria-hidden="true"></i>
          </button>

          <div v-if="feched">
            <div v-for="rubrique in rubriques_tree" v-bind:key="rubrique.id">
              <h4 style="margin: 15px 25px">{{ rubrique.label }}</h4>
              <ul class="list-unstyled" id="menu-left">
                <li
                  v-for="(lecon_item, index) in rubrique.lecons"
                  :key="lecon_item.id"
                >
                  <a
                    v-bind:class="{
                      active: lecon && lecon_item.id == lecon.id,
                    }"
                    href="#"
                    @click="
                      getLecon(
                        lecon_item.id,
                        index + 1 + '/' + rubrique.lecons.length
                      )
                    "
                    >{{ index + 1 }} • {{ lecon_item.label }}</a
                  >
                  <span class="chart" data-percent="0">
                    <span class="percent">0</span>
                  </span>
                </li>
              </ul>
            </div>
          </div>
          <div v-else></div>
        </div>
      </div>

      <div class="pull-right courses-content">
        <div class="courses-container pt-120" v-if="lecon" id="menu-left">
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <div class="courses-info text-center">
                <h6>
                  <img :src="'/assets/borne/img/book-heading.svg'" alt />
                  Leçon
                  <span>{{ lecon_index }}</span>
                </h6>
                <h2>{{ lecon.label }}</h2>
                <p>{{ lecon.introduction }}</p>
              </div>

              <div class="col-md-10 col-md-offset-1">
                <div class="panel-group wrap" id="bs-collapse">
                  <div class="panel">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#" href="#one"
                          >Objectifs Pédagogiques</a
                        >
                      </h4>
                    </div>
                    <div id="one" class="panel-collapse collapse">
                      <div class="panel-body" v-html="lecon.objectifs"></div>
                    </div>
                  </div>
                  <!-- end of panel -->
                  <div class="panel">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#" href="#1"
                          >Syllabus</a
                        >
                      </h4>
                    </div>
                    <div id="1" class="panel-collapse collapse">
                      <div class="panel-body" v-html="lecon.syllabus"></div>
                    </div>
                  </div>
                  <!-- end of panel -->

                  <div class="panel">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#" href="#3"
                          >Instructions</a
                        >
                      </h4>
                    </div>
                    <div id="3" class="panel-collapse collapse">
                      <div class="panel-body" v-html="lecon.instructions"></div>
                    </div>
                  </div>
                  <!-- end of panel -->

                  <div class="panel">
                    <div class="panel-heading">
                      <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#" href="#4"
                          >Prérequis</a
                        >
                      </h4>
                    </div>
                    <div id="4" class="panel-collapse collapse">
                      <div class="panel-body" v-html="lecon.prerequis"></div>
                    </div>
                  </div>
                  <!-- end of panel -->
                </div>
              </div>

              <div class="col-md-12 text-center">
                <router-link
                  :to="{ name: 'details_lecon', params: { id: lecon.id } }"
                  class="btn btn-main text-uppercase"
                >
                  DéMARRER LE COURS
                  <i class="material-icons rotate">&#xE317;</i>
                </router-link>
              </div>
            </div>
          </div>

          <div class="ressources">
            <div class="circle">
              <span></span>
            </div>
            <div class="col-md-12 text-center">
              <h3 class="text-uppercase">les Ressources de la leçon</h3>
            </div>
            <div class="clearfix"></div>
            <div class="timeline">
              <div
                v-for="ressource in ressources"
                v-bind:key="ressource.id"
                class="timeline-item"
              >
                <div class="row row-pad-xxs">
                  <div class="col-xs-2 text-center border">
                    <a href>
                      <span>
                        <img
                          :src="ressource.icon"
                          class="svg"
                          style="width:50px !important;height: 50px !important;"
                        />
                      </span>
                    </a>
                  </div>
                  <div class="col-xs-8">
                    <h4>{{ ressource.label }}</h4>
                    <p>{{ ressource.introduction }}</p>
                  </div>
                  <div class="col-xs-2 text-center">
                    <p class="time">
                      <img :src="'/assets/borne/img/clock.svg'" alt />
                      {{ ressource.duree }} min
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      optionsScroll: {
        height: "calc(100vh - 70px)",
        allowPageScroll: true,
        size: "5px",
        start: "bottom",
      },
      feched: false,
      date: "test",
      services: null,
      prestationcategories: [],
      prestations: [],
      alias: this.$route.params.alias,
      banner: null,
      salon_name: null,
      url: "borne_home",
      niveaux: [],
      unites: [],
      rubriques_tree: [],
      filter: {
        niveau_id: null,
        unite_id: null,
      },
      filter_title: {
        niveau_label: null,
        unite_label: null,
      },
      lecon: null,
      lecon_index: null,
      ressources: [],
    };
  },
  metaInfo() {
    return {
      title: `${(this.salon_name ? this.salon_name + " :" : "") +
        " Téléchargeable sur iPhone, iPad ou sur les appareils Android"}`,
    };
  },
  created() {
    this.fetch();
  },
  mounted() {
    let fil_id = JSON.stringify(this.$route.params.pathMatch).split("/");

    fil_id = fil_id[fil_id.length - 1].replace('"', "");
    this.getLecon(fil_id);
    LMSScriptHeader.init();
    LMSScript.init();
  },
  methods: {
    fetch() {
      this.feched = false;
      let params = {};
      $.each(this.filter, function(key, value) {
        params[key] = value;
      });
      axios
        .get(this.url, {
          params: params,
        })
        .then(
          function(response) {
            this.niveaux = response.data.niveaux;
            this.unites = response.data.unites;
            this.rubriques_tree = response.data.rubriques_tree;
            this.filter.niveau_id = response.data.niveau_id;
            this.filter.unite_id = response.data.unite_id;
            this.feched = true;

            if (this.filter.niveau_id) {
              setTimeout(function() {
                LMSScript.init();
              }, 500);
            }

            this.filter_title.niveau_label = this.niveaux.filter(
              (c) => c.value == this.filter.niveau_id
            )[0]["text"];
            this.filter_title.unite_label = this.unites.filter(
              (c) => c.value == this.filter.unite_id
            )[0]["text"];
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
    getLecon(lecon_id, lecon_index) {
      this.lecon_index = lecon_index;
      let params = {};
      axios
        .get("lecons/" + lecon_id, {
          params: params,
        })
        .then(
          function(response) {
            this.lecon = response.data.lecon;
            this.ressources = response.data.ressources;
            setTimeout(function() {
              LMSScript.init();
            }, 500);
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
    changeUnite(unite) {
      this.filter.unite_id = unite.value;
      this.$refs.uniteTrigger.click();
      this.lecon = null;
      this.fetch();
    },
    changeNiveau(niveau) {
      this.filter.niveau_id = niveau.value;
      this.$refs.niveauTrigger.click();
      this.lecon = null;
      this.fetch();
    },
  },
};
</script>
