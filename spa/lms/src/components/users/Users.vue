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
            Utilisateurs
          </h5>
          <div class="d-flex align-items-center" id="kt_subheader_search">
            <span class="text-dark-50 font-weight-bold" id="kt_subheader_total"
              >{{ usersCount }} Utilisateurs</span
            >
          </div>
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
          <a
            @click="addUser()"
            data-toggle="modal"
            href="#modal_form_rubrique"
            class="btn btn-primary btn-sm"
          >
            <i class="ki ki-plus icon-1x"></i> Ajouter un utilisateur
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
    <div class="pr-3 pl-3 mt-30">
      <div class="fx">
        <div class="box" v-for="(user, index) in users" :key="index">
          <div class="top-head">
            <v-menu bottom top>
              <template v-slot:activator="{ on, attrs }">
                <v-btn dark icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>

              <v-list>
                <div class="drop">
                  <button
                    data-toggle="modal"
                    href="#modal_form_rubrique"
                    @click="editUser(user)"
                    class="btn "
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      class="bi bi-pencil-square"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                      />
                      <path
                        fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"
                      />
                    </svg>
                    Modifier
                  </button>
                  <button
                    data-toggle="modal"
                    href="#modal_form_password"
                    @click="editUserPassword(user.id)"
                    class="btn "
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      class="bi bi-pen"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"
                      />
                    </svg>
                    Mot de passe
                  </button>
                  <button @click="deleteUser(user.id)" class="btn ">
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
                      /></svg
                    >Supprimer
                  </button>
                </div>
              </v-list>
            </v-menu>
          </div>
          <div class="head">
            <div class="img">
              <img :src="user.image" width="50px" alt="" />
            </div>
            <div class="text">
              <span>{{ user.firstName + " " + user.lastName }}</span> <br />
              <span :class="user.active ? 'activated' : 'not-activated'">{{
                user.active ? "active" : "not active"
              }}</span>
            </div>
          </div>
          <div class="body">
            <div class="user-info">
              <span> Email :</span><span>{{ user.email }}</span>
            </div>
            <div class="user-info">
              <span> Tél : </span><span>{{ user.gsm }}</span>
            </div>
            <div class="user-info">
              <span> Rôle :</span><span>{{ user.role }}</span>
            </div>
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
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="!user.id" class="modal-title" id="exampleModalLabel">
              Ajouter un utilisateur
            </h5>
            <h5 v-if="user.id" class="modal-title" id="exampleModalLabel">
              Modifier un utilisateur
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
              <div class="col-12 d-flex justify-center">
                <div class="form-group mt-2 text-center" style="width: 100px;">
                  <label class="d-block text-center">Text Image</label>
                  <div
                    class="image-input image-input-empty image-input-outline"
                    id="kt_user_edit_avatar"
                    v-bind:style="{
                      'background-image': 'url(' + user.image + ')',
                    }"
                  >
                    <div
                      class="image-input-wrapper"
                      style="width: 120px !important;height: 120px !important;"
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
              <div class="col-12 row">
                <div class="form-group col-6">
                  <label for="recipient-name" class="form-control-label"
                    >Nom :</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    placeholder="First Name"
                    v-model="user.firstName"
                  />
                  <span
                    v-show="error.errLabel"
                    class="alert alert-danger mt-2"
                    style="display: block;font-weight: 700;"
                    >{{ error.errLabel }}</span
                  >
                </div>
                <div class="form-group col-6">
                  <label for="recipient-name" class="form-control-label"
                    >Prénom :</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Last Name"
                    v-model="user.lastName"
                  />
                  <span
                    v-show="error.errLabel"
                    class="alert alert-danger mt-2"
                    style="display: block;font-weight: 700;"
                    >{{ error.errLabel }}</span
                  >
                </div>
              </div>
              <div class="col-12 row">
                <div class="form-group col-6">
                  <label for="recipient-name" class="form-control-label"
                    >GSM :</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Gsm"
                    v-model="user.gsm"
                  />
                  <span
                    v-show="error.errLabel"
                    class="alert alert-danger mt-2"
                    style="display: block;font-weight: 700;"
                    >{{ error.errLabel }}</span
                  >
                </div>
                <div class="form-group col-6">
                  <label for="recipient-name" class="form-control-label"
                    >Email :</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Email"
                    v-model="user.email"
                  />
                  <span
                    v-show="error.errLabel"
                    class="alert alert-danger mt-2"
                    style="display: block;font-weight: 700;"
                    >{{ error.errLabel }}</span
                  >
                </div>
              </div>
              <div class="col-12">
                <div class="form-group mb-0">
                  <label for="recipient-name" class="form-control-label">
                    Role
                  </label>
                  <v-autocomplete
                    v-model="user.role"
                    :items="roles"
                    dense
                    small-chips
                    filled
                  ></v-autocomplete>
                </div>
              </div>
              <div class="col-12">
                <v-checkbox
                  v-model="user.active"
                  label="Son compte est-il actif ?"
                ></v-checkbox>
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
                v-if="!user.id"
                niveau="button"
                @click="saveUser($event)"
                class="btn btn-primary spinner-darker-info spinner-right"
              >
                Enregistrer
              </button>
              <button
                v-if="user.id"
                niveau="button"
                @click="updateUser($event)"
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
    <!--start  modal  -->
    <div
      class="modal fade"
      id="modal_form_password"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Modifier mot de passe
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
              <div class="col-12 row">
                <div class="form-group col-6">
                  <label for="recipient-name" class="form-control-label"
                    >Nouveau mot de passe :</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    placeholder="Nouveau mot de passe"
                    v-model="user.password"
                  />
                  <span
                    v-show="error.errLabel"
                    class="alert alert-danger mt-2"
                    style="display: block;font-weight: 700;"
                    >{{ error.errLabel }}</span
                  >
                </div>
                <div class="form-group col-6">
                  <label for="recipient-name" class="form-control-label"
                    >Confirmer :</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    placeholder="Confirmer"
                    v-model="user.passwordComfirmation"
                  />
                  <span
                    v-show="error.errLabel"
                    class="alert alert-danger mt-2"
                    style="display: block;font-weight: 700;"
                    >{{ error.errLabel }}</span
                  >
                </div>
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
                niveau="button"
                @click="updateUserPassword($event)"
                class="btn btn-primary spinner-darker-info spinner-right"
              >
                Enregistrer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end password modal  -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      roles: [],
      usersCount: 0,
      user: {
        id: null,
        firstName: "",
        lastName: "",
        gsm: "",
        email: "",
        password: "",
        image: "",
        passwordComfirmation: "",
        active: false,
        role: null,
        value: "",
      },
      error: {
        errLabel: "",
      },
    };
  },
  mounted() {
    this.getUsersCount();
    this.getRoles();
    var avatar = new KTImageInput("kt_user_edit_avatar");
  },
  methods: {
    addUser() {
      this.user = {};
    },
    getUsersCount() {
      axios
        .get("/getUsers")
        .then((response) => {
          this.usersCount = response.data.usersCount;
          this.users = response.data.users;
        })
        .catch((error) => {
          console.log(error);
        });
      return 0;
    },
    getRoles() {
      axios
        .get("/getRoles")
        .then((response) => {
          this.roles = response.data.roles;
        })
        .catch((error) => {
          console.log(error);
        });
      return 0;
    },
    printExcelFile() {},
    saveUser(e) {
      e.preventDefault();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      const formData = new FormData();
      $.each(this.user, function(key, value) {
        formData.append(key, value ? value : "");
      });
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("/new_user", formData, config)
        .then((res) => {
          console.log(res);
          if (
            res.data ==
            "L'email que vous avez entré est déjà enregistrer par un autre user."
          ) {
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire(res.data, "", "error");
            return;
          } else if (
            res.data ==
            "Le numéro de téléphone que vous avez entré est déjà enregistrer par un autre user."
          ) {
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire(res.data, "", "error");
            return;
          } else {
            this.clearForm();
            $("#modal_form_rubrique").modal("toggle");
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire("L'utilisateur a été inséré!", "", "success");
            $("#modal_form_rubrique").hide();
            this.getUsersCount();
          }
        })
        .catch((error) => {});
      //   console.log(this.user);
    },
    updateUser(e) {
      e.preventDefault();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      const formData = new FormData();
      $.each(this.user, function(key, value) {
        formData.append(key, value ? value : "");
      });
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios
        .post("/update_user", formData, config)
        .then((res) => {
          if (res.data.isSuccess) {
            this.clearForm();
            $("#modal_form_rubrique").modal("toggle");
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire("L'utilisateur a été bien modifier!", "", "success");
            $("#modal_form_rubrique").hide();
            this.getUsersCount();
          } else if (res.data.emailExists) {
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire(
              "L'email que vous avez entré est déjà enregistrer par un autre user.",
              "",
              "error"
            );
          } else if (res.data.telExists) {
            e.target.disabled = false;
            e.target.classList.toggle("spinner");
            swal.fire(
              "Le numéro de téléphone que vous avez entré est déjà enregistrer par un autre user.",
              "",
              "error"
            );
          }
        })
        .catch((error) => {});
      //   console.log(this.user);
    },
    clearForm() {
      if (!this.user) {
        $.each(
          this.user,
          function(key, value) {
            this.user[key] = null;
          }.bind(this)
        );
      }
    },
    onImageTextChange(e) {
      this.user.image = e.target.files[0];
    },
    deleteUser(id) {
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
                .delete("/deleteUser/" + id)
                .then((response) => {
                  console.log(response);
                  swal.fire(
                    "Deleted!",
                    "Your file has been deleted.",
                    "success"
                  );
                  this.getUsersCount();
                })
                .catch((error) => console.log(error));
            }
          }.bind(this)
        );
    },
    updateUserPassword(e) {
      e.preventDefault();
      const formData = new FormData();
      formData.append("user_id", this.user.user_id);
      formData.append("password", this.user.password);
      formData.append("passwordComfirmation", this.user.passwordComfirmation);
      e.target.disabled = true;
      e.target.classList.toggle("spinner");
      axios.post("/updatePassword", formData).then((res) => {
        if (res.data.isPass) {
          swal.fire(
            "Le changement du mot de passe a été fait avec succés",
            "",
            "success"
          );
          $("#modal_form_password").modal("toggle");
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
        } else {
          swal.fire(res.data.msg, "", "error");
          e.target.disabled = false;
          e.target.classList.toggle("spinner");
        }
      });
      console.log(this.user);
    },
    editUserPassword(id) {
      this.user.user_id = id;
    },
    editUser(user) {
      this.user = user;
    },
  },
};
</script>

<style>
.container {
  margin-top: 100px;
}
.fx {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: repeat(3, 250px);
  gap: 20px;
}
.fx .box {
  padding: 20px;
  box-shadow: 0px 0px 1px 1px #0000001c;
  border-radius: 15px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.fx .box .top-head {
  margin-left: auto;
}
.fx .box .head {
  display: flex;
  align-items: flex-start;
}
.fx .box .head .img {
  margin-right: 5px;
}
.fx .box .head .text {
  font-weight: 600;
  color: black;
}
.fx .box .body .user-info {
  color: black;
  display: flex;
  justify-content: space-between;
}
.fx .box .body .user-info span:nth-child(2) {
  color: #babfcf;
}
.activated {
  background: #c9f7f5;
  padding: 5px 10px;
  border-radius: 5px;
  color: #1bc5bd;
  margin-top: 5px;
  width: fit-content;
  display: block;
}
.not-activated {
  background: #f7c9f3;
  padding: 5px 10px;
  border-radius: 5px;
  color: #c51b79;
  margin-top: 5px;
  width: fit-content;
  display: block;
}
.v-btn__content {
  background: #c9f7f5;
  border-radius: 26px;
  width: 35px;
  height: 35px;
  color: white;
}
.image-input .image-input-wrapper {
  width: 120px !important;
  height: 120px !important;
  border-radius: 0.42rem;
  background-repeat: no-repeat;
  background-size: cover;
}
.action-menu {
  display: block;
} /* for the label element associated with .custom-checkbox */
.v-label {
  margin-bottom: 0;
}
.col-12 {
  padding: 0 !important;
  margin: 0 !important;
}
.drop {
  display: flex !important;
  flex-direction: column;
  padding: 15px;
  height: 150px;
  justify-content: space-between;
  align-items: baseline;
}
.drop button {
  margin-bottom: 5px;
}
</style>
