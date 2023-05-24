<template>
  <div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div
      class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white"
      id="kt_login"
    >
      <!--begin::Aside-->
      <div
        class="login-aside order-2 order-lg-1 d-flex flex-row-auto position-relative overflow-hidden"
      >
        <!--begin: Aside Container-->
        <div
          class="d-flex flex-column-fluid flex-column justify-content-between py-9 px-7 py-lg-13"
        >
          <!--begin::Logo-->
          <a href="#" class="text-center pt-2">
            <img src="/../assets/media/logos/logo.png" class="max-h-40px" alt />
          </a>
          <!--end::Logo-->
          <!--begin::Aside body-->
          <div class="d-flex flex-column-fluid flex-column flex-center">
            <!--begin::Signin-->
            <div class="p-10" v-bind:class="{ 'overlay overlay-block': overlay }">
              <div class="overlay-wrapper">
                <div class="login-form login-forgot pt-11 d-block">
                  <!--begin::Form-->
                  <div class="text-center pb-8">
                    <h2
                      style="font-weight: 900 !important;"
                      class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg"
                    >Mot de passe oublié ?</h2>
                    <p
                      class="text-muted font-size-h6"
                    >Entrez votre e-mail pour réinitialiser votre mot de passe</p>
                  </div>
                  <div
                    v-if="has_success"
                    class="alert alert-custom alert-light-success fade show mb-5"
                    role="alert"
                  >
                    <div class="alert-icon">
                      <i class="flaticon-mail"></i>
                    </div>
                    <div class="alert-text">{{has_success}}</div>
                    <div class="alert-close">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">
                          <i class="ki ki-close"></i>
                        </span>
                      </button>
                    </div>
                  </div>
                  <form
                    v-if="!has_success"
                    class="form"
                    novalidate="novalidate"
                    id="kt_login_signin_forms"
                    autocomplete="off"
                    @submit.prevent="forgot_login"
                    method="post"
                  >
                    <!--begin::Title-->
                    <!--end::Title-->
                    <!--begin::Form group-->
                    <div class="form-group">
                      <input
                        class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6"
                        type="email"
                        v-model="email"
                        placeholder="Email"
                        required
                        name="email"
                        autocomplete="off"
                      />
                    </div>
                    <!--end::Form group-->

                    <validation-errors :errors="validationErrors" v-if="validationErrors"></validation-errors>
                    <!--begin::Form group-->
                    <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                      <button
                        type="submit"
                        id="kt_login_forgot_submit"
                        class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4"
                      >Réinitialiser</button>
                      <router-link
                        to="/login"
                        class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4"
                      >Retour</router-link>
                    </div>
                    <!--end::Form group-->
                  </form>
                  <!--end::Form-->
                </div>
              </div>
              <div class="overlay-layer bg-dark-o-10" v-if="overlay">
                <div class="spinner spinner-primary"></div>
              </div>
            </div>
          </div>
          <!--end::Aside body-->
        </div>
        <!--end: Aside Container-->
      </div>
      <!--begin::Aside-->
      <!--begin::Content-->
      <div
        class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0"
        style="background-color: #B1DCED;"
      >
        <!--begin::Title-->
        <div
          class="d-flex flex-column justify-content-center text-center pt-lg-20 pb-lg-20 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7"
        >
          <h3
            class="display4 font-weight-bolder my-7 text-dark"
            style="color: #986923;font-weight: 700 !important;"
          >lms</h3>
          <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70">
            lms est une plateforme qui met en relation les professeurs
            <br />avec les étudiant(e)s qui cherchent à apprendre
            <br />de nouvelles langues.
          </p>
        </div>
        <!--end::Title-->
        <!--begin::Image-->
        <div
          class="content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center"
          style="    background-image: url(/../assets/media/svg/illustrations/home1.png);
    min-height: 400px !important;
    width: 417px;
    background-size: cover;
    margin: auto;"
        ></div>
        <!--end::Image-->
      </div>
      <!--end::Content-->
    </div>
    <!--end::Login-->
  </div>
</template>

<script>
export default {
  data() {
    return {
      validationErrors: null,
      email: null,
      overlay: null,
      password: null,
      has_error: false,
      has_success: false,
      link_inscription: `${process.env.MIX_APP_URL}/inscription`,
    };
  },

  mounted() {
    //
  },

  methods: {
    forgot_login() {
      this.overlay = true;
      axios
        .post("auth/reset-password", {
          email: this.email,
        })
        .then((response) => {
          if (response.data.success) {
            this.has_success = response.data.message;
          }

          this.overlay = false;
        })
        .catch((error) => {
          this.overlay = false;
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
          }
        });
    },
  },
};
</script>
