<template>
  <div>
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
      <div
        class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap"
      >
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
          <!--begin::Page Title-->
          <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">
            Structure pédagogiques
          </h5>
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
          <a
            @click="addlecon()"
            data-toggle="modal"
            href="#modal_form_lecon"
            class="btn btn-nice btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i> Nouvelle leçon CP:
          </a>
          <button
            @click="printExcelFile"
            style="width:150px"
            class="btn btn-success ml-1"
          >
            Print excel
          </button>
          <a
            target="_blank"
            :href="url_base + '/lms/teaching/preview/'"
            class="ml-2 btn btn-info btn-sm"
          >
            <i class="flaticon-eye" style="padding: 0;"></i>
          </a>
        </div>
        <!--end::Toolbar-->
      </div>
    </div>

    <div class="d-flex flex-column-fluid">
      <div class="container-fluid">
        <div class="card card-custom gutter-b mb-1">
          <div class="card-body p-0 pr-8 pl-8">
            <div class="row justify-content-center font-inter font-weight-500">
              <div class="col-xl-4">
                <div class="form-group mb-0 d-flex align-items-center">
                  <label class="mr-2">Niveau</label>
                  <v-autocomplete
                    :items="niveaux"
                    @change="fetch()"
                    v-model="filter.niveau_id"
                    dense
                    filled
                  ></v-autocomplete>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="form-group mb-0 d-flex align-items-center">
                  <label class="mr-2">Unité</label>
                  <v-autocomplete
                    :items="unites"
                    @change="fetch()"
                    v-model="filter.unite_id"
                    dense
                    filled
                  ></v-autocomplete>
                </div>
              </div>
              <div class="col-xl-4" v-show="matieres.length > 1">
                <div class="form-group mb-0 d-flex align-items-center">
                  <label class="mr-2">Matieres</label>
                  <v-autocomplete
                    :items="matieres"
                    v-model="filter.matiere_id"
                    @change="showLecon()"
                    dense
                    filled
                  ></v-autocomplete>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row" v-if="emptyLecons && loaded">
          <div
            class="col-md-4"
            v-for="rubrique in rubriques_tree"
            v-bind:key="rubrique.id"
          >
            <div class="accordion accordion-solid accordion-toggle-plus">
              <div class="card w-100" style="overflow: hidden !important;">
                <div
                  class="card-header"
                  style="background: #efefef;color: black;"
                >
                  <div
                    class="card-title collapsed"
                    data-toggle="collapse"
                    :href="'#collapse-' + rubrique.id"
                    role="button"
                    aria-expanded="false"
                    :aria-controls="'collapse-' + rubrique.id"
                  >
                    <div>
                      <span
                        class="font-size-md font-size-bold"
                        style="font-size: 2rem;"
                      >
                        {{ rubrique.label }}
                        <span class="font-size-sm d-block mt-1"
                          >{{ rubrique.lecons.length }} leçons</span
                        >
                      </span>
                    </div>
                  </div>
                </div>
                <div class="collapse" :id="'collapse-' + rubrique.id">
                  <div class="card-body">
                    <div
                      class="navi navi-bold navi-hover navi-active navi-link-rounded"
                    >
                      <draggable
                        class="list-group"
                        style="height: 100%"
                        :list="rubrique.lecons"
                        @end="onEndDrag(rubrique.lecons)"
                        :group="'lecon' + rubrique.id"
                        itemKey="value"
                      >
                        <div
                          class="navi-item mb-1"
                          v-for="(lecon, index) in rubrique.lecons"
                          :key="lecon.id"
                        >
                          <router-link
                            :to="{
                              name: 'lecon_item',
                              params: { id: lecon.id },
                            }"
                            class="navi-link py-2 px-2 d-flex"
                          >
                            <span class="font-weight-bold font-size-md">
                              <span>{{ lecon.label }}</span>
                              <small class="d-block font-inter">
                                lecon {{ index + 1 }} -
                                {{ lecon.count_ressources }} ressources -
                                {{ lecon.duree }} minutes</small
                              >
                            </span>
                            <router-link
                              :to="{
                                name: 'lecon_item',
                                params: { id: lecon.id },
                              }"
                              style="margin-left: auto"
                              class="btn btn-icon btn-light btn-sm"
                            >
                              <span
                                class="svg-icon svg-icon-md svg-icon-success"
                              >
                                <i class="flaticon-information"></i>
                              </span>
                            </router-link>
                          </router-link>
                        </div>
                      </draggable>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          v-else-if="emptyLecons == false && loaded"
          style="height: 40vh;text-align: center;"
        >
          <img
            :src="url_base + '/assets/schools/lms/icons/empty-folder.png'"
            width="100%"
            height="80%"
            style="object-fit: contain;"
            alt=""
          />
          <h3>Aucune leçon.</h3>
          <a
            @click="addlecon()"
            data-toggle="modal"
            href="#modal_form_lecon"
            class="btn btn-nice btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i> Ajouter une nouvelle leçon à ce
            niveau/matière
          </a>
        </div>
        <div v-else-if="!loaded" style="height: 65vh;text-align: center;">
          <svg
            version="1.1"
            id="L9"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            height="50%"
            x="0px"
            y="0px"
            viewBox="0 0 100 100"
            enable-background="new 0 0 0 0"
            xml:space="preserve"
          >
            <path
              fill="#2196f3"
              d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50"
            >
              <animateTransform
                attributeName="transform"
                attributeType="XML"
                type="rotate"
                dur="1s"
                from="0 50 50"
                to="360 50 50"
                repeatCount="indefinite"
              />
            </path>
          </svg>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modal_form_lecon"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="!lecon.id" class="modal-title" id="exampleModalLabel">
              Nouvelle leçon CP:
            </h5>
            <h5 v-if="lecon.id" class="modal-title" id="exampleModalLabel">
              Modifier la leçon CP:
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="hideValidationsText"
            >
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <form class="mb-3">
            <div class="modal-body">
              <div class="col p-0">
                <div class="form-group mb-0">
                  <label for="recipient-name" class="form-control-label">
                    Unité
                    <b>*</b>
                  </label>
                  <!-- v-model="lecon.unite_id" -->
                  <v-autocomplete
                    :items="unites"
                    v-model="filter.unite_id"
                    dense
                    small-chips
                    filled
                  ></v-autocomplete>
                  <span
                    v-show="error.errorUnite"
                    class=" mt-2"
                    style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                    >{{ error.errorUnite }}</span
                  >
                </div>
              </div>
              <div class="col p-0" v-show="matieres.length > 1">
                <div class="form-group mb-0">
                  <label for="recipient-name" class="form-control-label">
                    Matieres
                    <b>*</b>
                  </label>
                  <v-autocomplete
                    v-model="lecon.matiere_id"
                    :items="matieres"
                    dense
                    small-chips
                    filled
                  ></v-autocomplete>
                  <span
                    v-show="error.errorMatiere"
                    class=" mt-2"
                    style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                    >{{ error.errorMatiere }}</span
                  >
                </div>
              </div>

              <div class="col p-0">
                <div class="form-group mb-0">
                  <label for="recipient-name" class="form-control-label">
                    Niveau
                    <b>*</b>
                  </label>
                  <v-autocomplete
                    v-if="lecon.niveau_id"
                    v-model="lecon.niveau_id"
                    :items="niveaux"
                    dense
                    small-chips
                    filled
                  ></v-autocomplete>
                  <v-autocomplete
                    v-else
                    v-model="filter.niveau_id"
                    :items="niveaux"
                    dense
                    small-chips
                    filled
                  ></v-autocomplete>
                  <span
                    v-show="error.errorNiveau"
                    class=" mt-2"
                    style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                    >{{ error.errorNiveau }}</span
                  >
                </div>
              </div>
              <div class="col p-0">
                <div class="form-group mb-0" v-if="!lecon.new_rubrique">
                  <label for="recipient-name" class="form-control-label">
                    Composante
                    <b>*</b>
                  </label>
                  <v-autocomplete
                    :disabled="lecon.new_rubrique"
                    v-model="lecon.rubrique_id"
                    :items="rubriques"
                    dense
                    small-chips
                    filled
                  ></v-autocomplete>
                  <span
                    v-show="error.errorComposante"
                    class=" mt-2"
                    style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                    >{{ error.errorComposante }}</span
                  >

                  <a
                    data-toggle="modal"
                    href="#modal_form_rubrique"
                    class="d-block text-right mt-1 font-size-sm"
                    >Nouvelle unité</a
                  >
                </div>
                <div class="form-group mb-0" v-if="lecon.new_rubrique">
                  <label for="recipient-name" class="form-control-label"
                    >Nouvelle unité</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nouvelle unité"
                    v-model="lecon.rubrique_label"
                  />
                  <a
                    @click="newRubrique(false)"
                    class="d-block text-right mt-1 font-size-sm"
                    >Choisir une unité</a
                  >
                </div>
              </div>
              <div class="form-group mb-4">
                <label for="recipient-name" class="form-control-label"
                  >Titre</label
                >
                <input
                  type="text"
                  class="form-control form-control-solid"
                  placeholder="Titre"
                  v-model="lecon.label"
                />
                <span
                  v-show="error.errorLabel"
                  class=" mt-2"
                  style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                  >{{ error.errorLabel }}</span
                >
              </div>
              <div class="form-group mb-4">
                <label for="recipient-name" class="form-control-label"
                  >Introduction</label
                >
                <textarea
                  class="form-control form-control-solid"
                  :placeholder="'Introduction'"
                  v-model="lecon.introduction"
                  rows="3"
                ></textarea>
                <span
                  v-show="error.errorIntro"
                  class=" mt-2"
                  style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                  >{{ error.errorIntro }}</span
                >
              </div>
              <div class="col-12 d-flex justify-center">
                <div class="form-group mt-2 text-center" style="width: 400px;">
                  <label class="d-block text-center">Lecon Image</label>
                  <div
                    class="image-input image-input-empty image-input-outline"
                    id="kt_lecon_edit_avatar"
                    v-bind:style="{
                      'background-image': 'url(' + lecon.icone + ')',
                    }"
                  >
                    <div
                      class="image-input-wrapper"
                      style="width: 400px !important;height: 250px !important;"
                    ></div>
                    <label
                      class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                      data-action="change"
                      data-toggle="tooltip"
                      title
                      data-original-title="Change avatar"
                    >
                      <i class="fa fa-pen icon-sm text-muted"></i>
                      <input
                        type="file"
                        name="image"
                        @change="onImageTextChange"
                        accept=".png, .jpg, .jpeg, .svg"
                      />
                      <input type="hidden" name="profile_avatar_remove" />
                    </label>
                    <span
                      class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                      data-action="cancel"
                      data-toggle="tooltip"
                      title="Cancel avatar"
                    >
                      <i class="ki ki-bold-close icon-xs text-muted"></i>
                    </span>
                    <span
                      class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                      data-action="remove"
                      data-toggle="tooltip"
                      title="Remove avatar"
                    >
                      <i class="ki ki-bold-close icon-xs text-muted"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div v-if="false" class="col">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label"
                      >Matière</label
                    >
                    <v-autocomplete
                      v-model="lecon.matiere_id"
                      :items="matieres"
                      clearable
                      dense
                      small-chips
                      filled
                    ></v-autocomplete>
                  </div>
                </div>
              </div>

              <validation-errors
                :errors="validationErrors"
                v-if="validationErrors"
              ></validation-errors>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="clearForm(), hideValidationsText()"
                class="btn btn-nice btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <button
                type="button"
                @click="saveLecon($event)"
                class="btn btn-nice btn-primary spinner-darker-info spinner-right"
              >
                Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      id="modal_form_rubrique"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Nouvelle rubrique:
            </h5>
            <!-- <h5 v-if="lecon.id" class="modal-title" id="exampleModalLabel">
              Modifier rubrique:
            </h5> -->
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <form class="mb-3">
            <div class="modal-body">
              <div class="form-group mb-4">
                <label for="recipient-name" class="form-control-label"
                  >Titre</label
                >
                <input
                  type="text"
                  class="form-control form-control-solid"
                  placeholder="Titre"
                  v-model="rubrique.label"
                />
                <span
                  v-show="rubrique.error.errorLabel"
                  class="alert alert-danger mt-2"
                  style="display: block;font-weight: 700;"
                  >{{ rubrique.error.errorLabel }}
                </span>
              </div>

              <validation-errors
                :errors="validationErrors"
                v-if="validationErrors"
              ></validation-errors>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="clearForm()"
                class="btn btn-nice btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <button
                type="button"
                @click="saveRubrique($event)"
                class="btn btn-nice btn-primary spinner-darker-info spinner-right"
              >
                Enregistrer
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
  components: {
    draggable,
  },
  data() {
    return {
      url: "lecons",
      url_base: `${document
        .querySelector("meta[name=base_api]")
        .getAttribute("content")}`,
      search: "",
      emptyLecons: null,
      loaded: null,
      headers: [
        {
          text: "Code",
          align: "middle",
          value: "code",
        },
        {
          text: "Niveau",
          align: "middle",
          value: "label",
        },
        { text: "", align: "right", value: "actions", sortable: false },
      ],
      validationErrors: [null],
      rubrique: {
        label: null,
        error: {
          errorLabel: null,
        },
      },
      lecons: [],
      niveaux: [],
      unites: [],
      matieres: [],
      rubriques: [],
      rubriques_tree: [],
      lecon: {
        id: null,
        icone: null,
        label: null,
        introduction: null,
        niveau_id: null,
        unites_id: null,
        matiere_id: null,
        rubrique_id: null,
        rubrique_label: null,
        new_rubrique: null,
      },
      filter: {
        niveau_id: null,
        unite_id: null,
        matiere_id: null,
      },
      edit: false,
      error: {
        errorIntro: "",
        errorLabel: "",
        errorComposante: "",
        errorUnite: "",
        errorNiveau: "",
        errorMatiere: "",
      },
    };
  },
  created() {
    this.fetch();
    var avatar = new KTImageInput("kt_lecon_edit_avatar");
  },
  watch: {
    matieres() {
      if (this.matieres.length <= 1) {
        this.filter.matiere_id = null;
        console.log(this.filter.matiere_id);
      }
    },
  },
  methods: {
    async fetch() {
      this.loaded = false;
      this.lecon.unite_id = this.filter.unite_id;
      let params = {};
      $.each(this.filter, function(key, value) {
        params[key] = value;
      });
      await axios
        .get(this.url, {
          params: params,
        })
        .then((response) => {
          this.lecons = response.data.lecons;
          this.niveaux = response.data.niveaux;
          this.unites = response.data.unites;
          this.rubriques = response.data.rubriques;
          this.rubriques_tree = response.data.rubriques_tree;
          if (this.rubriques_tree.length) {
            this.emptyLecons = true;
          } else if (this.rubriques_tree.length == 0) {
            this.emptyLecons = false;
          } else {
            this.emptyLecons = null;
          }
          if (!this.filter.unite_id) {
            this.filter.unite_id = response.data.unite_id;
          }
          if (!this.filter.niveau_id) {
            this.filter.niveau_id = response.data.niveau_id;
          }
          this.loaded = true;
        })
        .catch((error) => console.log(error));
    },
    deletelecon(id) {
      swal
        .fire({
          title: "Êtes-vous sûr ?",
          text: "Vous êtes sur le point de supprimer cette leçon !",
          lecon: "question",
          showCancelButton: true,
          confirmButtonText: "Supprimer",
          cancelButtonText: "Annuler",
        })
        .then(
          function(result) {
            if (result.value) {
              axios
                .post(this.url + "/" + id, { _method: "DELETE" })
                .then((response) => {
                  this.fetch();
                  swal.fire(
                    "lecon supprimé !",
                    "Le lecon a été supprimé avec succés.",
                    "success"
                  );
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    saveOrder(without_popup = false, data) {
      this.overlay = true;
      let params = {};
      params["data"] = data;
      axios
        .post("lecons/save_order", params)
        .then(
          function(response) {
            this.fetch();
            if (!without_popup)
              swal.fire(
                response.data.title,
                response.data.message,
                response.data.icon
              );
          }.bind(this)
        )
        .catch((error) => {
          // code here when an upload is not valid
          this.overlay = false;
          console.log("check error: ", this.error);
        });
    },
    saveLecon(e) {
      const config = {
        headers: { "content-lecon": "multipart/form-data" },
      };
      this.lecon.niveau_id = this.filter.niveau_id;
      this.lecon.unite_id = this.filter.unite_id;
      let formData = new FormData();
      $.each(this.lecon, function(key, value) {
        formData.append(key, value ? value : "");
      });
      let countErr = 0;
      if (!this.lecon.rubrique_id) {
        this.error.errorUnite = "Unite is required";
        countErr++;
      } else {
        this.error.errorUnite = "";
      }

      if (!this.lecon.rubrique_id) {
        this.error.errorComposante = "Composante is required";
        countErr++;
      } else {
        this.error.errorComposante = "";
      }
      if (!this.lecon.label) {
        this.error.errorLabel = "Label is required";
        countErr++;
      } else {
        this.error.errorLabel = "";
      }
      if (!this.lecon.niveau_id) {
        this.error.errorNiveau = "Niveau is required";
        countErr++;
      } else {
        this.error.errorNiveau = "";
      }
      if (countErr > 0) {
        return;
      }
      e.target.disabled = true;
      console.log("save");
      e.target.classList.toggle("spinner");
      axios
        .post(this.url, formData, config)
        .then((response) => {
          this.clearForm();
          $("#modal_form_lecon").modal("toggle");
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          this.error.errorUnite = "";
          this.error.errorIntro = "";
          this.error.errorComposante = "";
          this.error.errorLabel = "";
          this.error.errorNiveau = "";
          this.fetch();
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          }
        });
    },
    addlecon() {
      this.clearForm();
      this.validationErrors = null;
      var avatar = new KTImageInput("kt_lecon_edit_avatar");
    },
    onImageTextChange(e) {
      this.lecon.icone = e.target.files[0];
    },
    editlecon(lecon) {
      $("#modal_form_lecon").modal("toggle");
      this.validationErrors = null;
      this.edit = true;
      $.each(
        this.lecon,
        function(key, value) {
          this.lecon[key] = lecon[key];
        }.bind(this)
      );
    },
    clearForm() {
      this.edit = false;
      $.each(
        this.lecon,
        function(key, value) {
          this.lecon[key] = null;
        }.bind(this)
      );
    },
    newRubrique(bool) {
      this.lecon.new_rubrique = bool;
    },
    onImageChange(e) {
      this.lecon.image = e[0];
    },
    onEndDrag(data) {
      this.saveOrder(true, data);
    },
    saveRubrique($e) {
      $e.preventDefault();
      const config = {
        headers: { "content-lecon": "multipart/form-data" },
      };
      let formData = new FormData();
      $.each(this.rubrique, function(key, value) {
        formData.append(key, value ? value : "");
      });
      let countErr = 0;
      if (!this.rubrique.label) {
        this.rubrique.error.errorLabel = "Unite is required";
        countErr++;
      }
      if (countErr > 0) {
        return;
      }
      axios
        .post("/saveRubrique", formData)
        .then((response) => {
          this.clearForm();
          this.rubrique.label = "";
          $("#modal_form_rubrique").modal("toggle");
          this.rubrique.error.errorLabel = "";
          this.fetch();
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          }
        });
    },
    printExcelFile() {
      let unite_id = this.filter.unite_id ?? 1;
      let niveau_id = this.filter.niveau_id ?? 1;
      let matiere_id = this.filter.matiere_id ?? 1;
      axios
        .post("/exportFile", {
          responseType: "blob",
          formData: unite_id,
          niv: niveau_id,
          matiere: matiere_id,
        })
        .then((response) => {
          const url = URL.createObjectURL(
            new Blob([response.data.output], {
              type: "application/vnd.ms-excel",
            })
          );
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute(
            "download",
            "boti_lms_" +
              response.data.level +
              "_" +
              response.data.unite +
              ".xls"
          );
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    hideValidationsText() {
      this.error.errorUnite = "";
      this.error.errorIntro = "";
      this.error.errorComposante = "";
      this.error.errorLabel = "";
      this.error.errorNiveau = "";
    },
    showMatiere() {
      let unite_id = this.lecon.unite_id
        ? this.lecon.unite_id
        : filter.unite_id;
      this.filter.matiere_id = null;
      axios
        .post("/getMatiereFiltredByUnites", {
          formData: unite_id,
        })
        .then((response) => {
          this.matieres = response.data.matieres;
        })
        .catch((error) => console.log(error));
    },
    showLecon() {
      let unite_id = this.filter.unite_id;
      let niveau_id = this.filter.niveau_id;
      let matiere_id = this.filter.matiere_id ?? null;

      // return;
      axios
        .post("/getLeconByNiveauMatiereUnite", {
          unite: unite_id,
          niveau: niveau_id,
          matiere: matiere_id,
        })
        .then((response) => {
          this.rubrique.lecons = response.data.lecons;
          this.rubriques = response.data.rubriques;
          this.rubriques_tree = response.data.rubriques_tree;
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>
