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
            Types des étapes
          </h5>
          <div class="d-flex align-items-center" id="kt_subheader_search">
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total"
              >{{ rubriques.length }} Types des étapes</span
            >
            <form class="ml-5">
              <div
                class="input-group input-group-sm input-group-solid"
                style="max-width: 175px"
              >
                <input
                  type="text"
                  v-model="search"
                  class="form-control"
                  placeholder="Recherche..."
                />
                <div class="input-group-append">
                  <span class="input-group-text">
                    <span class="svg-icon">
                      <!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="24px"
                        height="24px"
                        viewBox="0 0 24 24"
                        version="1.1"
                      >
                        <g
                          stroke="none"
                          stroke-width="1"
                          fill="none"
                          fill-rule="evenodd"
                        >
                          <rect x="0" y="0" width="24" height="24" />
                          <path
                            d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                            fill="#000000"
                            fill-rule="nonzero"
                            opacity="0.3"
                          />
                          <path
                            d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                            fill="#000000"
                            fill-rule="nonzero"
                          />
                        </g>
                      </svg>
                      <!--end::Svg Icon-->
                    </span>
                    <!--<i class="flaticon2-search-1 icon-sm"></i>-->
                  </span>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
          <a
            @click="addRubrique()"
            data-toggle="modal"
            href="#modal_form_rubrique"
            class="btn btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i> Ajouter un type
          </a>
        </div>
        <!--end::Toolbar-->
      </div>
    </div>

    <div class="d-flex flex-column-fluid">
      <div class="container-fluid">
        <div class="card card-custom gutter-b">
          <div class="card-header">
            <div class="card-title">
              <h3 class="card-label">Types des étapes</h3>
            </div>
          </div>
          <div class="card-body p-2 pt-0">
            <!-- <v-card style="box-shadow: none;"> -->
            <v-data-table
              class="table"
              :headers="headers"
              :items="rubriques"
              :search="search"
            >
              <template v-slot:item.text="{ item }">
                <div
                  class="d-flex align-items-center"
                  :style="{ border: '2px solid ' + item.color }"
                  style="border-radius: 5px;padding: 5px;"
                >
                  <!--begin::Symbol-->
                  <div class="symbol symbol-70 symbol-light-success mr-2">
                    <img v-bind:src="item.image_url" alt="not found" />
                  </div>

                  <!--end::Symbol-->
                  <!--begin::Text-->
                  <div class="d-flex flex-column flex-grow-1">
                    <span
                      class="text-dark text-hover-primary font-weight-bold"
                      >{{ item.text }}</span
                    >
                  </div>
                  <div v-if="false" class="">
                    <span
                      class="color-picker ml-2"
                      :style="{ 'background-color': item.color }"
                    ></span>
                  </div>
                  <div>
                    <a
                      @click="editRubrique(item)"
                      href="javascript:void(0)"
                      style="margin-bottom: 5px;"
                      class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3"
                    >
                      <span class="svg-icon svg-icon-md svg-icon-primary">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg-->
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          xmlns:xlink="http://www.w3.org/1999/xlink"
                          width="24px"
                          height="24px"
                          viewBox="0 0 24 24"
                          version="1.1"
                        >
                          <g
                            stroke="none"
                            stroke-width="1"
                            fill="none"
                            fill-rule="evenodd"
                          >
                            <rect x="0" y="0" width="24" height="24" />
                            <path
                              d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                              fill="#000000"
                              fill-rule="nonzero"
                              transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"
                            />
                            <path
                              d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                              fill="#000000"
                              fill-rule="nonzero"
                              opacity="0.3"
                            />
                          </g>
                        </svg>
                        <!--end::Svg Icon-->
                      </span>
                    </a>
                  </div>
                  <!--end::Text-->
                </div>
              </template>
            </v-data-table>
            <!-- </v-card> -->
          </div>
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
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="!rubrique.id" class="modal-title" id="exampleModalLabel">
              Ajouter un type
            </h5>
            <h5 v-if="rubrique.id" class="modal-title" id="exampleModalLabel">
              Modifier un type
            </h5>
            <button
              @click="hideValidationsText"
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <div class="mb-3">
            <div class="modal-body">
              <validation-errors
                :errors="validationErrors"
                v-if="validationErrors"
              ></validation-errors>

              <div class="form-group mt-2 text-center">
                <label class="d-block text-center">Icone</label>
                <div
                  class="image-input image-input-empty image-input-outline"
                  id="kt_user_edit_avatar"
                  v-bind:style="{
                    'background-image': 'url(' + rubrique.image_url + ')',
                  }"
                >
                  <div class="image-input-wrapper"></div>
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
                      v-on:change="onImageChange"
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
              <div class="form-group">
                <label for="recipient-name" class="form-control-label"
                  >Libellé :</label
                >
                <input
                  type="text"
                  class="form-control"
                  placeholder="Libellé"
                  v-model="rubrique.text"
                />
                <span
                  v-show="errs.errLabel"
                  class=" mt-2"
                  style="border-color: transparent;color: #F64E60;background: transparent;display: block;font-weight: 700;"
                  >{{ errs.errLabel }}</span
                >
              </div>
              <div class="form-group">
                <label for="recipient-name" class="form-control-label"
                  >étape color :</label
                >
                <input
                  type="color"
                  class="form-control color-in"
                  placeholder="color"
                  v-model="rubrique.color"
                />
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="
                  clearForm();
                  hideValidationsText();
                "
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <button
                niveau="button"
                @click="saveRubrique($event)"
                class="btn btn-primary spinner-darker-info spinner-right"
              >
                Enregistrer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      url: "etape_types",
      search: "",
      headers: [
        {
          text: "Type",
          align: "middle",
          value: "text",
        },
      ],
      validationErrors: null,
      rubriques: [],
      rubrique: {
        id: null,
        text: null,
        icon: null,
        color: null,
        image_url: null,
      },
      edit: false,
      errs: {
        errLabel: "",
      },
    };
  },
  mounted() {
    var avatar = new KTImageInput("kt_user_edit_avatar");
  },
  created() {
    this.fetch();
  },
  methods: {
    fetch() {
      axios
        .get(this.url)
        .then((response) => (this.rubriques = response.data.rubriques))
        .catch((error) => console.log(error));
    },
    deleteRubrique(id) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
        })
        .then(
          function(result) {
            if (result.value) {
              axios
                .delete(this.url + "/" + id)
                .then((response) => {
                  swal.fire(
                    "Deleted!",
                    "Your file has been deleted.",
                    "success"
                  );
                  this.fetch();
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    saveRubrique(e) {
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };

      let formData = new FormData();
      $.each(this.rubrique, function(key, value) {
        formData.append(key, value);
      });
      let countErr = 0;
      if (!this.rubrique.text) {
        this.errs.errLabel = "Label is required";
        countErr++;
      }
      if (countErr > 0) {
        return;
      }
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post(this.url, formData, config)
        .then((response) => {
          this.clearForm();
          $("#modal_form_rubrique").modal("toggle");
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          this.fetch();
          this.errs.errLabel = "";
        })
        .catch((error) => {
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    addRubrique() {
      var avatar = new KTImageInput("kt_user_edit_avatar");
      this.clearForm();
      this.validationErrors = null;
    },
    editRubrique(rubrique) {
      $("#modal_form_rubrique").modal("toggle");
      $("#kt_user_edit_avatar .image-input-wrapper").css(
        "background-image",
        "none"
      );

      this.validationErrors = null;
      this.edit = true;
      $.each(
        this.rubrique,
        function(key, value) {
          this.rubrique[key] = rubrique[key];
        }.bind(this)
      );
    },
    clearForm() {
      this.edit = false;
      $.each(
        this.rubrique,
        function(key, value) {
          this.rubrique[key] = null;
        }.bind(this)
      );
    },
    onImageChange(e) {
      this.rubrique.image_url = "";
      this.rubrique.icon = e.target.files[0];
      console.log(this.rubrique.icon);
    },
    hideValidationsText() {
      this.errs.errLabel = "";
    },
  },
};
</script>
<style scoped>
.v-data-table::v-deep table tbody {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
}
.v-data-table::v-deep table tbody tr {
  margin-right: 50px;
  margin-top: 20px;
  border: none;
}
.v-data-table::v-deep table tbody tr td {
  padding: 0;
  display: initial;
  margin: 10px 0;
}
.color-in-sec {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 100px;
  height: 100px;
  background-color: transparent;
  border: none;
  cursor: pointer;
}
.color-in-sec::-webkit-color-swatch {
  border-radius: 15px;
  border: none;
}
.color-in-sec::-moz-color-swatch {
  border-radius: 15px;
  border: none;
}
.color-picker {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: block;
  margin: auto;
  border: 1px solid #d9d9d9;
}
.color-in {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: transparent;
  width: 100px;
  height: 100px;
  border: none;
  cursor: pointer;
}
.color-in::-webkit-color-swatch {
  border-radius: 50%;
  border: 2px solid #d9d9d9;
}
.color-in::-moz-color-swatch {
  border-radius: 50%;
  border: 2px solid #d9d9d9;
}

@media (max-width: 850px) {
  table tbody {
    grid-template-columns: 1fr;
  }
}
</style>
