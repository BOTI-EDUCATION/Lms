<template>
  <div class="d-flex flex-column flex-root" v-if="show">
    <!--begin::Login-->
    <div
      class="
        login login-2 login-signin-on
        d-flex
        flex-column flex-lg-row flex-column-fluid
        bg-white
      "
      id="kt_login"
    >
      <!--begin::Aside-->
      <div
        class="
          login-aside
          order-2 order-lg-1
          d-flex
          flex-row-auto
          position-relative
          overflow-hidden
        "
      >
        <!--begin: Aside Container-->
        <div
          class="
            d-flex
            flex-column-fluid flex-column
            justify-content-between
            py-9
            px-7
            py-lg-13
            px-lg-35
          "
        >
          <!--begin::Logo-->
          <a href="#" class="text-center pt-2">
            <img
              src="/../assets/media/logos/otm_logo.svg"
              class="max-h-50px"
              alt
            />
          </a>
          <!--end::Logo-->
          <!--begin::Aside body-->
          <div class="d-flex flex-column-fluid flex-column flex-center">
            <!--begin::Signin-->
            <div class="login-form login-signin py-11">
              <!--begin::Form-->
              <form
                class="form"
                novalidate="novalidate"
                id="kt_login_signin_forms"
                autocomplete="off"
                @submit.prevent="login"
                method="post"
              >
                <!--begin::Title-->
                <div class="text-center pb-8">
                  <h2
                    class="
                      font-weight-bolder
                      text-dark
                      font-size-h1 font-size-h1-lg
                    "
                    style="font-weight: 700 !important"
                  >
                    Se connecter
                  </h2>
                </div>
                <!--end::Title-->
                <div
                  class="
                    alert alert-custom alert-light-danger
                    fade
                    show
                    mb-5
                    p-4
                  "
                  v-if="has_error"
                  role="alert"
                >
                  <div class="alert-icon">
                    <i class="flaticon-warning"></i>
                  </div>
                  <div class="alert-text">{{ has_error }}</div>
                  <div class="alert-close">
                    <button
                      type="button"
                      class="close"
                      data-dismiss="alert"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">
                        <i class="ki ki-close"></i>
                      </span>
                    </button>
                  </div>
                </div>
                <!--begin::Form group-->
                <div class="form-group">
                  <label class="font-size-h6 font-weight-bolder text-dark"
                    >Email</label
                  >
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                    "
                    v-model="email"
                    placeholder="Email"
                    name="email"
                    autocomplete="off"
                    required
                    type="text"
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group">
                  <div class="d-flex justify-content-between mt-n5">
                    <label
                      class="font-size-h6 font-weight-bolder text-dark pt-5"
                      >Mot de passe</label
                    >
                    <router-link
                      to="/login"
                      class="
                        text-primary
                        font-size-h6 font-weight-bolder
                        text-hover-primary
                        pt-5
                      "
                      id="kt_login_forgot"
                      >Mot de passe oublié ?</router-link
                    >
                  </div>
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                    "
                    type="password"
                    v-model="password"
                    placeholder="Mot de passe"
                    name="password"
                    autocomplete="off"
                    required
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Action-->
                <div class="text-center pt-2">
                  <button
                    id="kt_login_signin_submit"
                    type="submit"
                    class="
                      btn btn-dark
                      font-weight-bolder font-size-h6
                      px-8
                      py-4
                      my-3
                    "
                  >
                    Se connecter
                  </button>
                </div>
                <!--end::Action-->
              </form>
              <!--end::Form-->
            </div>
            <!--end::Signin-->
            <!--begin::Signup-->
            <div class="login-form login-signup pt-11">
              <!--begin::Form-->
              <form
                class="form"
                novalidate="novalidate"
                id="kt_login_signup_form"
              >
                <!--begin::Title-->
                <div class="text-center pb-8">
                  <h2
                    class="
                      font-weight-bolder
                      text-dark
                      font-size-h2 font-size-h1-lg
                    "
                  >
                    Sign Up
                  </h2>
                  <p class="text-muted font-weight-bold font-size-h4">
                    Enter your details to create your account
                  </p>
                </div>
                <!--end::Title-->
                <!--begin::Form group-->
                <div class="form-group">
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                      font-size-h6
                    "
                    type="text"
                    placeholder="Fullname"
                    name="fullname"
                    autocomplete="off"
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group">
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                      font-size-h6
                    "
                    type="email"
                    placeholder="Email"
                    name="email"
                    autocomplete="off"
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group">
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                      font-size-h6
                    "
                    type="password"
                    placeholder="Password"
                    name="password"
                    autocomplete="off"
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group">
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                      font-size-h6
                    "
                    type="password"
                    placeholder="Confirm password"
                    name="cpassword"
                    autocomplete="off"
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div class="form-group">
                  <label class="checkbox mb-0">
                    <input type="checkbox" name="agree" />I Agree the
                    <a href="#">terms and conditions</a>.
                    <span></span>
                  </label>
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div
                  class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3"
                >
                  <button
                    type="button"
                    id="kt_login_signup_submit"
                    class="
                      btn btn-primary
                      font-weight-bolder font-size-h6
                      px-8
                      py-4
                      my-3
                      mx-4
                    "
                  >
                    Submit
                  </button>
                  <button
                    type="button"
                    id="kt_login_signup_cancel"
                    class="
                      btn btn-light-primary
                      font-weight-bolder font-size-h6
                      px-8
                      py-4
                      my-3
                      mx-4
                    "
                  >
                    Cancel
                  </button>
                </div>
                <!--end::Form group-->
              </form>
              <!--end::Form-->
            </div>
            <!--end::Signup-->
            <!--begin::Forgot-->
            <div class="login-form login-forgot pt-11">
              <!--begin::Form-->
              <form
                class="form"
                novalidate="novalidate"
                id="kt_login_forgot_form"
              >
                <!--begin::Title-->
                <div class="text-center pb-8">
                  <h2
                    class="
                      font-weight-bolder
                      text-dark
                      font-size-h2 font-size-h1-lg
                    "
                  >
                    Forgotten Password ?
                  </h2>
                  <p class="text-muted font-weight-bold font-size-h4">
                    Enter your email to reset your password
                  </p>
                </div>
                <!--end::Title-->
                <!--begin::Form group-->
                <div class="form-group">
                  <input
                    class="
                      form-control form-control-solid
                      h-auto
                      py-7
                      px-6
                      rounded-lg
                      font-size-h6
                    "
                    type="email"
                    placeholder="Email"
                    name="email"
                    autocomplete="off"
                  />
                </div>
                <!--end::Form group-->
                <!--begin::Form group-->
                <div
                  class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3"
                >
                  <button
                    type="button"
                    id="kt_login_forgot_submit"
                    class="
                      btn btn-primary
                      font-weight-bolder font-size-h6
                      px-8
                      py-4
                      my-3
                      mx-4
                    "
                  >
                    Submit
                  </button>
                  <button
                    type="button"
                    id="kt_login_forgot_cancel"
                    class="
                      btn btn-light-primary
                      font-weight-bolder font-size-h6
                      px-8
                      py-4
                      my-3
                      mx-4
                    "
                  >
                    Cancel
                  </button>
                </div>
                <!--end::Form group-->
              </form>
              <!--end::Form-->
            </div>
            <!--end::Forgot-->
          </div>
          <!--end::Aside body-->
        </div>
        <!--end: Aside Container-->
      </div>
      <!--begin::Aside-->
      <!--begin::Content-->
      <div
        class="
          login-content
          content
          order-1 order-lg-2
          d-flex
          flex-column
          w-100
          pb-0
        "
        style="background-color: #b1dced"
      >
        <!--begin::Title-->
        <div
          class="
            d-flex
            flex-column
            justify-content-center
            text-center
            pt-lg-20
            pb-lg-20
            pt-md-5 pt-sm-5
            px-lg-0
            pt-5
            px-7
          "
        >
          <h3
            class="display4 font-weight-bolder my-7 text-dark"
            style="color: #986923; font-weight: 700 !important"
          >
            OTM Express
          </h3>
          <p
            class="
              font-weight-bolder font-size-h2-md font-size-lg
              text-dark
              opacity-70
            "
          >
            Partenaire Des E-Commerçants Depuis De Plusieurs
            <br />Années, OTMExpress S’adapte Aux Évolutions <br />Du Marché
            E-Commerce Au Maroc.
          </p>
        </div>
        <!--end::Title-->
        <!--begin::Image-->
        <div
          class="
            content-img
            d-flex
            flex-row-fluid
            bgi-no-repeat bgi-position-y-bottom bgi-position-x-center
          "
          style="
            background-image: url(/../assets/media/svg/illustrations/login_2.svg);
            min-height: 370px !important;
          "
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
      email: null,
      show: false,
      password: null,
      has_error: false,
      link_inscription:
        document.querySelector("meta[name=base_api]").getAttribute("content") +
        `/inscription`,
      link_forgotpassword: document
        .querySelector("meta[name=base_api]")
        .getAttribute("content")`/admin0619/forgot-password`,
    };
  },

  mounted() {
    //
  },
  created() {
    //
    this.show = true;
  },

  methods: {
    login() {
      // get the redirect object
      var redirect = this.$auth.redirect();
      var app = this;
      this.$auth
        .login({
          params: {
            email: app.email,
            password: app.password,
          },
          success: function (test) {
            app.has_error = null;
            // handle redirection
            //const redirectTo = redirect ? redirect.from.nom : app.$auth.user().role_id === 2 ? 'admin.dashboard' : 'dashboard'
            //console.log(app.$router);
          },
          error: function (error) {
            app.has_error = error.response.data.error;
          },
          rememberMe: true,
          fetchUser: true,
        })
        .then((response) => {
          window.location.href =
            document
              .querySelector("meta[name=base_api]")
              .getAttribute("content") + `/admin0619`;
        });
    },
  },
};
</script>
