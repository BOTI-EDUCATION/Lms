<template>
  <div></div>
</template>

<script>
export default {
  data() {
    return {
      salon: null,
      url: "sidebare",
    };
  },
  created() {},
  mounted() {
    console.log("Component mounted.");
  },
  watch: {
    "$route.params.alias": {
      handler: function (search) {
        //this.fetch(search);
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    fetch(alias) {
      let params = {};
      if (this.date) {
        params["date"] = this.date;
      }
      params["alias"] = alias;
      axios
        .get(this.url, {
          params: params,
        })
        .then(
          function (response) {
            this.salon = response.data.salon;
          }.bind(this)
        )
        .catch((error) => console.log(error));
    },
  },
};
</script>
