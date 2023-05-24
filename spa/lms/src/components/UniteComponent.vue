<template>
  <div>
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
      <div
        class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap"
      >
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
          <!--begin::Page Title-->
          <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Unités</h5>
          <div class="d-flex align-items-center" id="kt_subheader_search">
            <span
              class="text-dark-50 font-weight-bold"
              id="kt_subheader_total"
            >{{unites.length}} Unités</span>
            <form class="ml-5">
              <div class="input-group input-group-sm input-group-solid" style="max-width: 175px">
                <input type="text" v-model="search" class="form-control" placeholder="Recherche..." />
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
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
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
            @click="addunite()"
            data-toggle="modal"
            href="#modal_form_unite"
            class="btn btn-nice btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i> Ajouter une unité
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
              <h3 class="card-label">Unités</h3>
            </div>
          </div>
          <div class="card-body p-2 pt-0">
            <v-card style="box-shadow: none;">
              <v-data-table class="table" :headers="headers" :items="unites" :search="search">
                <template v-slot:item.label="{ item }">
                  <span class="font-weight-bold">{{ item.label }}</span>
                </template>
                <template v-slot:item.rib="{ item }">
                  <span class="font-weight-bold">{{ item.rib }}</span>
                </template>
                <template v-slot:item.count_agences="{ item }">
                  <span
                    class="label label-lg label-light-primary label-inline"
                  >{{ item.count_agences }} agences</span>
                </template>
                <template v-slot:item.count_points_relais="{ item }">
                  <span
                    class="label label-lg label-light-warning label-inline"
                  >{{ item.count_points_relais }} points relais</span>
                </template>
                <template v-slot:item.actions="{ item }">
                  <a
                    @click="editunite(item)"
                    href="javascript:void(0)"
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
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
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
                    href="#"
                    @click="deleteunite(item.id)"
                    class="btn btn-icon btn-light btn-hover-primary btn-sm"
                  >
                    <span class="svg-icon svg-icon-md svg-icon-primary">
                      <!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg-->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="24px"
                        height="24px"
                        viewBox="0 0 24 24"
                        version="1.1"
                      >
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <rect x="0" y="0" width="24" height="24" />
                          <path
                            d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
                            fill="#000000"
                            fill-rule="nonzero"
                          />
                          <path
                            d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                            fill="#000000"
                            opacity="0.3"
                          />
                        </g>
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

    <div
      class="modal fade"
      id="modal_form_unite"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="!unite.id" class="modal-title" id="exampleModalLabel">Ajouter une unité</h5>
            <h5 v-if="unite.id" class="modal-title" id="exampleModalLabel">Modifier une unité</h5>
            <button unite="button" class="close" data-dismiss="modal" aria-label="Close">
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <form class="mb-3">
            <div class="modal-body">
              <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
              <div class="form-group d-none">
                <label for="recipient-name" class="form-control-label">Code :</label>
                <input unite="number" class="form-control" placeholder="Code" v-model="unite.code" />
              </div>
              <div class="form-group">
                <label for="recipient-name" class="form-control-label">Libellé :</label>
                <input type="text" class="form-control" placeholder="Libellé" v-model="unite.label" />
              </div>
            </div>
            <div class="modal-footer">
              <button
                unite="button"
                @click="clearForm()"
                class="btn btn-secondary"
                data-dismiss="modal"
              >Fermer</button>
              <button
                unite="button"
                @click="saveunite($event)"
                class="btn btn-primary spinner-darker-info spinner-right"
              >Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      url: "unites",
      search: "",
      headers: [
        {
          text: "Unité",
          align: "middle",
          value: "label",
        },
        { text: "", align: "right", value: "actions", sortable: false },
      ],
      validationErrors: null,
      unites: [],
      unite: {
        id: null,
        code: null,
        label: null,
      },
      edit: false,
    };
  },
  created() {
    this.fetch();
  },
  methods: {
    fetch() {
      axios
        .get(this.url)
        .then(
          function (response) {
            this.unites = response.data.unites;
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
    deleteunite(id) {
      swal
        .fire({
          title: "Êtes-vous sûr ?",
          text: "Vous êtes sur le point de supprimer ce unite !",
          unite: "question",
          showCancelButton: true,
          confirmButtonText: "Supprimer",
          cancelButtonText: "Annuler",
        })
        .then(
          function (result) {
            if (result.value) {
              axios
                .post(this.url + "/" + id, { _method: "DELETE" })
                .then((response) => {
                  this.fetch();
                  swal.fire(
                    "unite supprimé !",
                    "Le unite a été supprimé avec succés.",
                    "success"
                  );
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    saveunite(e) {
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      const config = {
        headers: { "content-unite": "multipart/form-data" },
      };

      let formData = new FormData();
      $.each(this.unite, function (key, value) {
        formData.append(key, value ? value : "");
      });

      axios
        .post(this.url, formData, config)
        .then((response) => {
          this.clearForm();
          $("#modal_form_unite").modal("toggle");
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          this.fetch();
        })
        .catch((error) => {
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    addunite() {
      this.clearForm();
      this.validationErrors = null;
    },
    editunite(unite) {
      $("#modal_form_unite").modal("toggle");
      this.validationErrors = null;
      this.edit = true;
      $.each(
        this.unite,
        function (key, value) {
          this.unite[key] = unite[key];
        }.bind(this)
      );
    },
    clearForm() {
      this.edit = false;
      $.each(
        this.unite,
        function (key, value) {
          this.unite[key] = null;
        }.bind(this)
      );
    },
    onImageChange(e) {
      this.unite.image = e[0];
    },
  },
};
</script>
