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
            Niveaux
          </h5>
          <div class="d-flex align-items-center" id="kt_subheader_search">
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total"
              >{{ levelsLength }} Niveaux</span
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
            @click="addUnite()"
            data-toggle="modal"
            href="#modal_form_rubrique"
            class="btn btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i> Ajouter une unité
          </a>
          <!-- <button
            @click="printExcelFile"
            style="width:150px;margin-left:7px;"
            class="btn btn-success btn-sm"
          >
            Print excel
          </button> -->
        </div>
        <!--end::Toolbar-->
      </div>
    </div>
    <div class="d-flex flex-column-fluid">
      <div class="container-fluid">
        <div class="card card-custom gutter-b">
          <div class="card-header">
            <div class="card-title">
              <h3 class="card-label">Niveaux</h3>
            </div>
          </div>
          <div class="card-body p-2 pt-0">
            <v-card style="box-shadow: none;">
              <v-data-table
                class="table"
                :headers="headers"
                :items="levels"
                :search="search"
              >
                <template v-slot:item.text="{ item }">
                  <div class="d-flex align-items-center">
                    <!--begin::Text-->
                    <div class="d-flex flex-column flex-grow-1">
                      <span
                        class="text-dark text-hover-primary font-weight-bold"
                        >{{ item.text }}</span
                      >
                    </div>
                    <!--end::Text-->
                  </div>
                </template>
                <template v-slot:item.actions="{ item }">
                  <a
                    @click="editLevel(item)"
                    href="javascript:void(0)"
                    style="margin-bottom: 5px;"
                    class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3"
                  >
                    <span class="svg-icon svg-icon-md svg-icon-primary">
                      <!-- begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg -->
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
                  <a
                    @click="deleteLevel(item)"
                    href="javascript:void(0)"
                    class="btn btn-icon btn-light btn-hover-danger btn-sm mx-3"
                  >
                    <span class="svg-icon  svg-icon-md svg-icon-danger">
                      <!-- begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg -->

                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-trash"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                        />
                        <path
                          fill-rule="evenodd"
                          d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                        />
                      </svg>
                      <!--end::Svg Icon-->
                    </span>
                  </a>
                </template>
              </v-data-table>
            </v-card>
          </div>
        </div>
      </div>
    </div>

    <!--start  modal  -->
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
            <h5 v-if="!level.value" class="modal-title" id="exampleModalLabel">
              Ajouter un type
            </h5>
            <h5 v-if="level.value" class="modal-title" id="exampleModalLabel">
              Modifier un type
            </h5>
            <button
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
              <div class="form-group">
                <label for="recipient-name" class="form-control-label"
                  >Libellé :</label
                >
                <input
                  type="text"
                  class="form-control"
                  placeholder="Libellé"
                  v-model="level.text"
                />
                <span
                  v-show="error.errLabel"
                  class="alert alert-danger mt-2"
                  style="display: block;font-weight: 700;"
                  >{{ error.errLabel }}</span
                >
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="clearForm()"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Fermer
              </button>
              <button
                v-if="!this.edit"
                niveau="button"
                @click="saveLevel($event)"
                class="btn btn-primary spinner-darker-info spinner-right"
              >
                Enregistrer
              </button>
              <button
                v-if="this.edit"
                niveau="button"
                @click="updateLevel($event)"
                class="btn btn-primary spinner-darker-info spinner-right"
              >
                Modifier
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end  modal  -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: "",
      headers: [
        {
          text: "Type",
          align: "middle",
          value: "text",
        },
        { text: "", align: "right", value: "actions", sortable: false },
      ],
      levelsLength: 0,
      levels: [],
      level: {
        text: null,
        value: null,
      },
      error: {
        errLabel: null,
      },
      edit: false,
    };
  },
  mounted() {
    this.getLevels();
  },
  methods: {
    // get unites length
    getLevels() {
      axios
        .get("/getLevels")
        .then((response) => {
          this.levelsLength = response.data.levelsCount;
          this.levels = response.data.levels;
        })
        .catch((error) => {
          console.log(error);
        });
      return 0;
    },
    // clear data
    clearForm() {
      this.edit = false;
      this.error.errLabel = "";
      $.each(
        this.level,
        function(key, value) {
          this.level[key] = null;
        }.bind(this)
      );
    },
    updateLevel(e) {
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      $.each(this.level, function(key, value) {
        formData.append(key, value);
      });
      let countErr = 0;
      if (!this.level.text) {
        this.error.errLabel = "Label is required";
        countErr++;
      }
      if (countErr > 0) {
        return;
      }
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("/updateLevel", formData, config)
        .then((response) => {
          if (response.data.isSaved) {
            swal.fire(
              "Niveaux " + response.data.level + " à été bien modifier",
              "",
              "success"
            ); 
            this.clearForm();
            $("#modal_form_rubrique").modal("toggle");
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            this.getLevels();
            this.error.errLabel = "";
            countErr = 0;
          } else if (response.data.isExists) {
            swal.fire("Niveau déja existe", "", "error");
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          } else {
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire('Something wen"t wrong', "", "error");
          }
        })
        .catch((error) => {
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    // show modal in edition
    editLevel(level) {
      $("#modal_form_rubrique").modal("toggle");

      this.edit = true;
      $.each(
        this.level,
        function(key, value) {
          this.level[key] = level[key];
        }.bind(this)
      );
    },
    // delete a level
    deleteLevel(level) {
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
                .delete("/deleteLevel/" + level.value)
                .then((response) => {
                  if (response.data.isDeleted) {
                    swal.fire(
                      "Deleted!",
                      "Your level has been deleted.",
                      "success"
                    );
                    this.getLevels();
                  } else {
                    swal.fire(
                      "Not Deleted !!",
                      "can't delete this item",
                      "error"
                    );
                  }
                  this.levels();
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    // save level
    saveLevel(e) {
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };

      let formData = new FormData();
      $.each(this.level, function(key, value) {
        formData.append(key, value);
      });
      let countErr = 0;
      if (!this.level.text) {
        this.error.errLabel = "Label is required";
        countErr++;
      }
      if (countErr > 0) {
        return;
      }
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("/saveLevel", formData, config)
        .then((response) => {
          if (response.data.isSaved) {
            swal.fire(
              "Niveaux " + response.data.level + " à été bien insére",
              "",
              "success"
            );
            this.clearForm();
            $("#modal_form_rubrique").modal("toggle");
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            this.getLevels();
            this.error.errLabel = "";
            countErr = 0;
          } else if (response.data.isExists) {
            swal.fire("Niveau déja existe", "", "error");
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
          } else {
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire('Something wen"t wrong', "", "error");
          }
        })
        .catch((error) => {
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    // add modal unite
    addUnite() {
      var avatar = new KTImageInput("kt_user_edit_avatar");
      this.clearForm();
      this.validationErrors = null;
    },
    printExcelFile() {
      axios
        .post("/exportComposantesFile", {
          responseType: "blob",
        })
        .then((response) => {
          const url = URL.createObjectURL(
            new Blob([response.data.output], {
              type: "application/vnd.ms-excel",
            })
          );
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "composantes.xls");
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style></style>
