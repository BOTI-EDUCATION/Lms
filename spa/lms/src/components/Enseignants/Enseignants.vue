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
            Les enseignants
          </h5>
          <div class="d-flex align-items-center" id="kt_subheader_search">
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total"
              >{{ enseignants.length }} Enseignants</span
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
            class="btn btn-primary btn-sm d-none"
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
              <h3 class="card-label">les enseignants</h3>
            </div>
          </div>
          <div class="card-body p-2 pt-0">
            <v-card style="box-shadow: none;">
              <v-data-table
                class="table"
                :headers="headers"
                :items="enseignants"
                :search="search"
              >
                <template v-slot:item.name="{ item }">
                  <div class="d-flex align-items-center">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-70 symbol-light-success mr-2">
                      <img v-bind:src="item.image_url" alt />
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Text-->
                    <div class="d-flex flex-column flex-grow-1">
                      <span
                        class="text-dark text-hover-primary font-weight-bold"
                        >{{ item.name }}</span
                      >
                    </div>
                    <!--end::Text-->
                  </div>
                </template>
                <template v-slot:item.tel="{ item }">
                  <div class="d-flex align-items-center">
                    <!--begin::Text-->
                    <div class="d-flex flex-column flex-grow-1">
                      <span
                        class="text-dark text-hover-primary font-weight-bold"
                        >{{ item.tel }}</span
                      >
                    </div>
                    <!--end::Text-->
                  </div>
                </template>
                <template v-slot:item.classes="{ item }">
                  <div class="d-flex align-items-center">
                    <!--begin::Text-->
                    <div class="d-flex flex-column flex-grow-1">
                      <span
                        class="text-dark text-hover-primary font-weight-bold"
                        >{{ item.classes }}</span
                      >
                    </div>
                    <!--end::Text-->
                  </div>
                </template>
                <template v-slot:item.unites="{ item }">
                  <div class="d-flex align-items-center">
                    <!--begin::Text-->
                    <div class="d-flex flex-column flex-grow-1">
                      <span
                        class="text-dark text-hover-primary font-weight-bold"
                        >{{ item.unites }}</span
                      >
                    </div>
                    <!--end::Text-->
                  </div>
                </template>
              </v-data-table>
            </v-card>
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
      enseignants: [],
      search: "",
      headers: [
        {
          text: "Name",
          align: "middle",
          value: "name",
        },
        {
          text: "Tél",
          align: "middle",
          value: "tel",
        },
        {
          text: "Classes",
          align: "middle",
          value: "classes",
        },
        {
          text: "Unites",
          align: "middle",
          value: "unites",
        },
      ],
    };
  },
  methods: {
    addRubrique() {},
    editRubrique(item) {},
    async getEnseignants() {
      await axios
        .get("/enseignants")
        .then((response) => {
          this.enseignants = response.data.enseignants;
        console.log(this.enseignants)
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getEnseignants();
  },
};
</script>

<style></style>
