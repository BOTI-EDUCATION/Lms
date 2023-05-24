<template>
  <div id="kt_header" class="kt-header kt-grid__item kt-header--fixed">
    <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper"></div>
    <!-- begin:: Header Topbar -->
    <div class="kt-header__topbar">
      <!--begin: User Bar -->
      <div class="kt-header__topbar-item kt-header__topbar-item--user">
        <div
          class="kt-header__topbar-wrapper"
          data-toggle="dropdown"
          data-offset="0px,0px"
        >
          <div class="kt-header__topbar-user">
            <span class="kt-header__topbar-welcome kt-hidden-mobile"
              >Bonjour,</span
            >
            <span
              v-if="$auth.check()"
              class="kt-header__topbar-username kt-hidden-mobile"
              >{{ $auth.user()["prenom"] }}</span
            >
            <img class="kt-hidden" alt="Pic" src />
            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
            <span
              class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"
              >E</span
            >
          </div>
        </div>
        <div
          class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl"
        >
          <!--begin: Head -->
          <div
            class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x"
            style="background-image: url(/../assets/media/misc/bg-1.jpg)"
          >
            <div class="kt-user-card__avatar">
              <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
              <span
                class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"
                >E</span
              >
            </div>
            <div v-if="$auth.check()" class="kt-user-card__name">
              {{ $auth.user()["prenom"] + " " + $auth.user()["nom"] }}
            </div>
          </div>

          <!--end: Head -->

          <!--begin: Navigation -->
          <div class="kt-notification">
            <router-link to="/profile" class="kt-notification__item">
              <div class="kt-notification__item-icon">
                <i class="flaticon2-calendar-3 kt-font-success"></i>
              </div>
              <div class="kt-notification__item-details">
                <div class="kt-notification__item-title kt-font-bold">
                  Mon Profile
                </div>
                <div class="kt-notification__item-time">
                  Editer mes informations perso, Mot de passe...
                </div>
              </div>
            </router-link>
            <div class="kt-notification__custom kt-space-between">
              <div>
                <button
                  @click.prevent="$auth.logout()"
                  class="btn btn-label btn-label-brand btn-sm btn-bold"
                >
                  Se déconnecter
                </button>
              </div>
            </div>
          </div>

          <!--end: Navigation -->
        </div>
      </div>

      <!--end: User Bar -->
    </div>

    <!-- end:: Header Topbar -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      open: false,
    };
  },
};
</script>
