<template>
  <div>
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
      <div
        class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap"
      >
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
          <!--begin::Page Title-->
          <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">
            Tableau de bord
          </h5>
          <!--end::Page Title-->
          <!--begin::Actions-->
          <!--end::Actions-->
        </div>
        <!--end::Info-->
      </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Dashboard-->
    <div class="mr-3 ml-3">
      <div class="row">
        <div class="col-sm card mr-2 mb-2" style="">
          <h1>{{ leconsCount }}</h1>

          <h2>Leçons</h2>
        </div>
        <div class="col-sm card mr-2 mb-2" style="">
          <h1>{{ ressourceCount }}</h1>
          <h2>Ressources</h2>
        </div>
        <div class="col-sm card mr-2 mb-2" style="">
          <h1>{{ enseignantCount }}</h1>
          <h2>Enseignants</h2>
        </div>
      </div>
    </div>
    <h1 class="p-20 text-center font-weight-bold text-dark"></h1>
    <!--end::Dashboard-->
    <div
      class="modal fade"
      id="modal_actualite_item"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document" v-if="actualite_item">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ actualite_item.label }}
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
          <div class="modal-body">
            <div class="d-flex align-items-center">
              <!--begin::Symbol-->
              <div class="symbol symbol-40 symbol-light-success mr-5">
                <span class="symbol-label">
                  <img
                    src="assets/media/lms_logo_symbole.svg"
                    class="h-50"
                    alt
                  />
                </span>
              </div>
              <!--end::Symbol-->
              <!--begin::Info-->
              <div class="d-flex flex-column flex-grow-1">
                <a
                  href="#"
                  class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder"
                  >lms</a
                >
                <span class="text-muted font-weight-bold">{{
                  actualite_item.date_format
                }}</span>
              </div>
              <!--end::Info-->
            </div>
            <div
              class="bgi-no-repeat bgi-size-cover rounded min-h-265px mt-4"
              v-bind:style="{
                'background-image': 'url(' + actualite_item.image_url + ')',
              }"
            ></div>
            <div class="pt-4" v-html="actualite_item.description"></div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="fl-bx"
      style="display: flex; height: 400px;justify-content: space-evenly;width: 100%;align-items: center;"
    >
      <div
        style="background-color:white;padding:10px;width:48%;height:100%;border-radius:10px;box-shadow: 11px 11px 5px -4px rgba(222,222,222,0.75);"
      >
        <label class="chart_label">Lecons par unités</label>
        <canvas
          id="myChart"
          style="width: 325px;height: 325px;margin:auto"
        ></canvas>
      </div>
      <div
        style="background-color:white;padding:10px;width:48%;height:100%;border-radius:10px;box-shadow: 11px 11px 5px -4px rgba(222,222,222,0.75);"
      >
        <label class="chart_label">Lecons par niveaux</label>
        <canvas
          style="width: 325px;height: 325px ;margin:auto"
          id="myChart_"
        ></canvas>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      leconsCount: 0,
      ressourceCount: 0,
      enseignantCount: 0,
      uL: [],
      uV: [],
      date:
        new Date().toISOString().substr(0, 10) +
        " - " +
        new Date().toISOString().substr(0, 10),
      actualites: [],
      colis_count: {
        all: 0,
        en_attentes: 0,
        livres: 0,
        retournes: 0,
        professeurs: 0,
        clients: 0,
      },
      actualite_item: null,
      ca_chiffres: {
        ca_clients: 0,
        total_frais: 0,
        ca_clients_net: 0,
        commission_professeurs: 0,
        virements_clients: 0,
        ca_plateforme: 0,
      },
      document_count: {
        bons_livraison: 0,
        bons_retour: 0,
        factures: 0,
      },
      url: "dashboard",
    };
  },
  created() {
    this.fetch();
  },
  mounted() {
    this.getLeconCount();
    this.daterangepickerInit();

    let labels = [];
    let dat = [];
    axios
      .get("/dashboard")
      .then((response) => {
        labels = response.data.uL;
        dat = response.data.uV;
        const ctx = document.getElementById("myChart");
        new Chart(ctx, {
          type: "pie",
          data: {
            labels: labels,
            datasets: [
              {
                label: "lecons per unités",
                data: dat,
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
        const ctx_ = document.getElementById("myChart_");
        labels = new Chart(ctx_, {
          type: "pie",
          data: {
            labels: response.data.cL,
            datasets: [
              {
                label: "lecons per niveaux",
                data: response.data.cV,

                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
      })
      .catch((error) => console.log(error));
  },
  methods: {
    getLeconCount() {
      axios
        .get("/dashboard")
        .then((response) => {
          (this.leconsCount = response.data.leconsCount),
            (this.enseignantCount = response.data.ensCount),
            (this.ressourceCount = response.data.resCount);
        })
        .catch((error) => console.log(error));
    },
    fetch() {
      let params = {};
      if (this.date) {
        params["date"] = this.date;
      }
      axios
        .get(this.url, {
          params: params,
        })
        .then(
          function(response) {
            this.count_reservations = response.data.count_reservations;
            this.count_commandes = response.data.count_commandes;
            this.count_notations = response.data.count_notations;
            this.count_participations = response.data.count_participations;
            this.sum_commandes = response.data.sum_commandes;
            this.sum_reservations = response.data.sum_reservations;
            this.actualites = response.data.actualites;
            this.colis_count = response.data.colis_count;
            this.document_count = response.data.document_count;
            this.ca_chiffres = response.data.ca_chiffres;
            KTDashboard.init(response.data.charts);
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
    actualiteDetails(actualite) {
      this.actualite_item = actualite;
    },
    cb(start, end, label) {
      var title = "";
      var range = "";
      var input = "";

      if (end - start < 100 || label == "Aujourd'hui") {
        title = "Aujourd'hui :";
        range = start.format("D MMM Y");
        input = start.format("Y-MM-DD") + " - " + start.format("Y-MM-DD");
      } else if (label == "Hier") {
        title = "Hier :";
        range = start.format("D MMM Y");
        input = start.format("Y-MM-DD") + " - " + start.format("Y-MM-DD");
      } else {
        range = start.format("D MMM Y") + " - " + end.format("D MMM Y");
        input = start.format("Y-MM-DD") + " - " + end.format("Y-MM-DD");
      }

      $("#kt_dashboard_daterangepicker_date").html(range);
      $("#kt_dashboard_daterangepicker_title").html(title);
      $("#kt_dashboard_daterangepicker_input").val(input);

      this.date = input;
      this.fetch();
    },
    daterangepickerInit() {
      if ($("#kt_dashboard_daterangepicker").length == 0) {
        return;
      }

      var picker = $("#kt_dashboard_daterangepicker");
      var start = moment().subtract(6, "days");
      var end = moment();

      picker.daterangepicker(
        {
          direction: KTUtil.isRTL(),
          startDate: start,
          endDate: end,
          opens: "left",
          ranges: {
            "Aujourd'hui": [moment(), moment()],
            Hier: [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Derniers 7 jours": [moment().subtract(6, "days"), moment()],
            "Derniers 30 jours": [moment().subtract(29, "days"), moment()],
            "Ce mois": [moment().startOf("month"), moment().endOf("month")],
            "Mois précédent": [
              moment()
                .subtract(1, "month")
                .startOf("month"),
              moment()
                .subtract(1, "month")
                .endOf("month"),
            ],
            "Cette année": [moment().startOf("year"), moment().endOf("year")],
          },
        },
        this.cb
      );
      this.cb(start, end, "");
    },
  },
};
</script>
<style scoped>
.row-fx {
  display: flex;
}
.row-fx .box {
  border: 1px solid #b2b6cd;
  display: flex;
  padding: 10px 30px;
  align-items: center;
  margin-right: 30px;
  width: 100%;
  height: 120px;
  color: black;
}
.chart_label {
  font-weight: 800;
  border: 1px solid #2196f3;
  padding: 5px;
  border-radius: 5px;
  color: #2196f3;
}
.row-fx .box h1,
.row-fx .box h2 {
  margin: 0;
}
.row-fx .box h1 {
  margin-right: 5px;
}
@media (max-width: 667px) {
  .row-fx {
    flex-direction: column;
    height: 100%;
  }
  .row-fx .box {
    margin-top: 10px;
    width: 100%;
  }
  .fl-bx {
    height: 100% !important;
    flex-direction: column;
    margin-top: 10px;
    justify-content: flex-start !important;
  }
  .fl-bx div {
    width: 100% !important;
    height: 300px !important;
    margin-top: 10px;
  }
}
</style>
