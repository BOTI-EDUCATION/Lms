<template>
  <div>
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
      <div
        class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap"
      >
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
          <!--begin::Page Title-->
          <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Utilisateurs</h5>
          <!--end::Page Title-->
          <!--begin::Actions-->
          <div class="d-flex align-items-center" id="kt_subheader_search">
            <span
              class="text-dark-50 font-weight-bold"
              id="kt_subheader_total"
            >{{utilisateurs.length}} Utilisateurs</span>
          </div>
          <!--end::Actions-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
          <a
            @click="addUtilisateur()"
            data-toggle="modal"
            href="#modal_form_utilisateur"
            class="btn btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i>Ajouter un utilisateur
          </a>
        </div>
        <!--end::Toolbar-->
      </div>
    </div>
    <div class="d-flex flex-column-fluid">
      <div class="container-fluid">
        <div class="row">
          <div
            class="col-xl-3 col-lg-6 col-md-6 col-sm-6"
            v-for="utilisateur in utilisateurs"
            v-bind:key="utilisateur.id"
          >
            <!--begin::Card-->
            <div class="card">
              <!--begin::Body-->
              <div class="card-body pt-4">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end">
                  <div
                    class="dropdown dropdown-inline"
                    data-toggle="tooltip"
                    title
                    data-placement="left"
                    data-original-title="Quick actions"
                  >
                    <a
                      href="#"
                      class="btn btn-clean btn-hover-light-primary btn-sm btn-icon"
                      data-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="ki ki-bold-more-hor"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                      <!--begin::Navigation-->
                      <ul class="navi navi-hover">
                        <li class="navi-header font-weight-bold py-4">
                          <span class="font-size-lg">Actions :</span>
                          <i
                            class="flaticon2-information icon-md text-muted"
                            data-toggle="tooltip"
                            data-placement="right"
                            title
                            data-original-title="Click to learn more..."
                          ></i>
                        </li>
                        <li class="navi-separator mb-3 opacity-70"></li>
                        <li class="navi-item">
                          <a @click="editUtilisateur(utilisateur)" class="navi-link">
                            <span class="navi-text">
                              <i class="flaticon2-edit"></i>
                              <span class="ml-2">Modifier</span>
                            </span>
                          </a>
                        </li>
                        <li class="navi-item" v-if="$auth.user()['role_id'] == 2">
                          <a
                            @click="codesRetour(utilisateur)"
                            data-toggle="modal"
                            href="#modal_codes_retour"
                            class="navi-link"
                          >
                            <span class="navi-text">
                              <i class="flaticon-security"></i>
                              <span class="ml-2">Codes de retour</span>
                            </span>
                          </a>
                        </li>
                        <li class="navi-item">
                          <a
                            @click="modalPassword(utilisateur)"
                            data-toggle="modal"
                            href="#modal_password"
                            class="navi-link"
                          >
                            <span class="navi-text">
                              <i class="flaticon-security"></i>
                              <span class="ml-2">Mot de passe</span>
                            </span>
                          </a>
                        </li>
                        <li class="navi-item">
                          <a @click="deleteUtilisateur(utilisateur.id)" class="navi-link">
                            <span class="navi-text">
                              <i class="flaticon2-rubbish-bin-delete-button"></i>
                              <span class="ml-2">Supprimer</span>
                            </span>
                          </a>
                        </li>
                      </ul>
                      <!--end::Navigation-->
                    </div>
                  </div>
                </div>
                <!--end::Toolbar-->
                <!--begin::User-->
                <div class="d-flex align-items-end mb-7">
                  <!--begin::Pic-->
                  <div class="d-flex align-items-center">
                    <!--begin::Pic-->
                    <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                      <div class="symbol symbol-circle symbol-50">
                        <img
                          class="kt-widget__img kt-hidden-"
                          v-bind:src="utilisateur.image_url"
                          alt="image"
                        />
                      </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Title-->
                    <div class="d-flex flex-column">
                      <a
                        href="#"
                        class="text-dark font-weight-bold text-hover-primary mb-0"
                      >{{ utilisateur.nom }} {{ utilisateur.prenom }}</a>
                      <span
                        v-if="utilisateur.enabled"
                        class="label label-inline label-lg label-light-success font-weight-bold mt-1"
                      >Actif</span>
                      <span
                        v-if="!utilisateur.enabled"
                        class="label label-inline label-lg label-light-danger font-weight-bold mt-1"
                      >Inactif</span>
                    </div>
                    <!--end::Title-->
                  </div>
                  <!--end::Title-->
                </div>
                <!--end::User-->
                <!--begin::Desc-->
                <p class="mb-7"></p>
                <!--end::Desc-->
                <!--begin::Info-->
                <div class="mb-7 mt-20">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark-75 font-weight-bolder mr-2">Email :</span>
                    <a
                      href="#"
                      class="text-muted text-hover-primary font-size-sm"
                    >{{ utilisateur.email }}</a>
                  </div>
                  <div class="d-flex justify-content-between align-items-cente my-1">
                    <span class="text-dark-75 font-weight-bolder mr-2">Tél. :</span>
                    <a
                      href="#"
                      class="text-muted text-hover-primary font-size-sm"
                    >{{ utilisateur.tel }}</a>
                  </div>
                  <div class="d-flex justify-content-between align-items-cente my-1">
                    <span class="text-dark-75 font-weight-bolder mr-2">Rôle :</span>
                    <a
                      href="#"
                      v-if="utilisateur.acces_id == 1"
                      class="text-muted text-hover-primary font-size-sm"
                    >Administrateur</a>
                    <a
                      href="#"
                      v-if="utilisateur.acces_id == 2"
                      class="text-muted text-hover-primary font-size-sm"
                    >Responsable pédagogique</a>
                    <a
                      href="#"
                      v-if="utilisateur.acces_id == 3"
                      class="text-muted text-hover-primary font-size-sm"
                    >Traducteur</a>
                  </div>
                </div>
                <!--end::Info-->
              </div>
              <!--end::Body-->
            </div>
            <!--end::Card-->
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modal_form_utilisateur"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5
              v-if="!utilisateur.id"
              class="modal-title"
              id="exampleModalLabel"
            >Ajouter un membre d'équipe</h5>
            <h5
              v-if="utilisateur.id"
              class="modal-title"
              id="exampleModalLabel"
            >Modifier un membre d'équipe</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="save" class="mb-3">
            <div class="modal-body">
              <div class="form-group mt-2 text-center">
                <div
                  class="image-input image-input-empty image-input-outline"
                  id="kt_user_edit_avatar"
                  v-bind:style="{ 'background-image': 'url(' + utilisateur.image_url + ')' }"
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
                      name="profile_avatar"
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
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">Nom</label>
                    <input
                      type="text"
                      autocomplete="off"
                      class="form-control"
                      placeholder="Nom"
                      v-model="utilisateur.nom"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">Prénom</label>
                    <input
                      type="text"
                      autocomplete="off"
                      class="form-control"
                      placeholder="Prénom"
                      v-model="utilisateur.prenom"
                    />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">GSM</label>
                    <input
                      type="text"
                      class="form-control"
                      autocomplete="off"
                      placeholder="GSM"
                      v-model="utilisateur.tel"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">Email</label>
                    <input
                      type="text"
                      class="form-control"
                      autocomplete="off"
                      placeholder="Emaill"
                      v-model="utilisateur.email"
                    />
                  </div>
                </div>
              </div>

              <div class="row" v-if="$auth.user()['role_id'] == 1 && false">
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">CNIE</label>
                    <input
                      type="text"
                      autocomplete="off"
                      class="form-control"
                      placeholder="CNIE"
                      v-model="utilisateur.cni"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">Fonction</label>
                    <input
                      type="text"
                      autocomplete="off"
                      class="form-control"
                      placeholder="Fonction"
                      v-model="utilisateur.fonction"
                    />
                  </div>
                </div>
              </div>

              <div class="row" v-if="$auth.user()['role_id'] == 1">
                <div class="col-md-12 pt-0">
                  <div class="form-group mb-0">
                    <label for="recipient-name" class="form-control-label">Rôle</label>
                    <v-autocomplete
                      v-model="utilisateur.acces_id"
                      :items="acces"
                      clearable
                      dense
                      filled
                    ></v-autocomplete>
                  </div>
                </div>
              </div>
              <div class="form-group mb-0">
                <div class="checkbox-inline mb-2">
                  <label class="checkbox checkbox-lg">
                    <input type="checkbox" v-model="utilisateur.enabled" name="enabled" />
                    <span></span>Son compte est-il actif ?
                  </label>
                </div>
              </div>

              <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                @click="clearForm()"
                class="btn btn-secondary"
                data-dismiss="modal"
              >Fermer</button>
              <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modal_password"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Changer le mot de passe</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="savePassword" class="mb-3">
            <div class="card-body">
              <!--begin::Row-->
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-12">
                  <!--begin::Group-->
                  <div class="form-group row">
                    <label
                      class="col-form-label col-3 text-lg-right text-left"
                    >Nouveau mot de passe :</label>
                    <div class="col-9 pt-0 pb-0">
                      <input
                        class="form-control form-control-lg form-control-solid"
                        type="password"
                        v-model="password.nouveau"
                        placeholder="Nouveau mot de passe"
                      />
                    </div>
                  </div>
                  <!--end::Group-->
                  <!--begin::Group-->
                  <div class="form-group row">
                    <label class="col-form-label col-3 text-lg-right text-left">Confirmer</label>
                    <div class="col-9 pt-0 pb-0">
                      <input
                        class="form-control form-control-lg form-control-solid"
                        type="password"
                        v-model="password.confirmation"
                        placeholder="Confirmer"
                      />
                    </div>
                  </div>
                  <!--end::Group-->
                </div>
              </div>
              <!--end::Row-->
              <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
            </div>
            <!--end::Body-->
            <div class="modal-footer">
              <button
                type="button"
                @click="clearForm()"
                class="btn btn-secondary"
                data-dismiss="modal"
              >Fermer</button>
              <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modal_codes_retour"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Codes de retour</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <div class="modal-body">
            <div
              class="alert alert-custom alert-secondary mb-0"
              role="alert"
              v-if="utilisateur_codes"
            >
              <div class="alert-text">
                <p>
                  <b>Code retour :</b>
                  {{ utilisateur_codes.code_retour }}
                </p>
                <p class="mb-0">
                  <b>Mot de passe :</b>
                  {{ utilisateur_codes.password_retour }}
                </p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
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
      url: "utilisateurs",
      validationErrors: null,
      ville_search: null,
      utilisateurs: [],
      utilisateur_id: null,
      utilisateur_codes: null,
      utilisateur_password: null,
      villes: [],
      acces: [],
      clients: [],
      utilisateur: {
        id: null,
        nom: null,
        prenom: null,
        tel: null,
        image: null,
        image_url: null,
        ville_id: null,
        cni: null,
        fonction: null,
        enabled: null,
        code_retour: null,
        password_retour: null,
        rubriques_id: [],
        clients_id: [],
        email: null,
        acces_id: null,
      },
      password: {
        actuel: null,
        nouveau: null,
        confirmation: null,
      },
      edit: false,
    };
  },
  mounted() {
    var avatar = new KTImageInput("kt_user_edit_avatar");
    if (this.$route.query.add) {
      $("#modal_form_utilisateur").modal("toggle");
    }
  },
  created() {
    this.fetch();
  },
  methods: {
    fetch(page_url) {
      let params = {};
      if (this.ville_search) {
        params["ville_search"] = this.ville_search;
      }
      axios
        .get(this.url, {
          params: params,
        })
        .then(
          function (response) {
            this.utilisateurs = response.data.utilisateurs;
            if (response.data.villes) {
              this.villes = response.data.villes;
              this.ville_search = this.villes[0].value;
            }
            if (response.data.acces) {
              this.acces = response.data.acces;
            }
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
    modalPassword(utilisateur) {
      this.utilisateur_password = utilisateur;
    },
    savePassword() {
      let formData = {};
      $.each(this.password, function (key, value) {
        formData[key] = value;
      });

      formData["id"] = this.utilisateur_password.id;

      axios
        .post("utilisateurs/change_password", formData)
        .then((response) => {
          this.validationErrors = null;

          $("#modal_password").modal("toggle");
          swal.fire(
            "Votre mot de passe a été changé avec succès !",
            "",
            "success"
          );
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    deleteUtilisateur(id) {
      swal
        .fire({
          title: "Êtes-vous sûr ?",
          text: "Vous êtes sur le point de supprimer cet utilisateur !",
          type: "question",
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
                    "Utilisateur supprimé !",
                    "Cet utilisateur a été supprimé avec succés.",
                    "success"
                  );
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    sendPassword(id) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "Oui !",
        })
        .then(
          function (result) {
            if (result.value) {
              let params = {};
              params["utilisateur"] = id;
              axios
                .get(this.url + "/password", {
                  params: params,
                })
                .then((response) => {
                  swal.fire(
                    "Email envoyé !",
                    "Merci de consulter votre boite email, un email vous a été envoyé, contenant votre nouveau mot de passe.",
                    "success"
                  );
                  this.fetch();
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    save() {
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };

      let formData = new FormData();
      $.each(this.utilisateur, function (key, value) {
        formData.append(key, value ? value : "");
      });

      axios
        .post(this.url, formData, config)
        .then((response) => {
          this.clearForm();
          $("#modal_form_utilisateur").modal("toggle");
          this.fetch();
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    saveCompetences() {
      let params = {};
      params["utilisateur"] = this.utilisateur_id;
      params["competences"] = this.utilisateur_competences;

      axios
        .post(this.url + "/competences", params)
        .then((response) => {
          $("#modal_form_competences").modal("toggle");
          this.fetch();
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
    addUtilisateur() {
      this.clearForm();
      this.validationErrors = null;
      var avatar = new KTImageInput("kt_user_edit_avatar");
    },
    codesRetour(utilisateur) {
      this.utilisateur_codes = utilisateur;
    },
    editUtilisateur(utilisateur) {
      $("#modal_form_utilisateur").modal("toggle");
      this.validationErrors = null;
      this.edit = true;
      var avatar = new KTImageInput("kt_user_edit_avatar");
      $.each(
        this.utilisateur,
        function (key, value) {
          this.utilisateur[key] = utilisateur[key];
        }.bind(this)
      );
    },
    utilisateurCompetences(utilisateur) {
      this.validationErrors = null;
      this.edit = true;
      this.utilisateur_id = utilisateur.id;
      this.utilisateur_competences = utilisateur.competences;
    },
    clearForm() {
      this.edit = false;
      $.each(
        this.utilisateur,
        function (key, value) {
          this.utilisateur[key] = null;
        }.bind(this)
      );
    },
    onImageChange(e) {
      this.utilisateur.image = e.target.files[0];
    },
  },
};
</script>
