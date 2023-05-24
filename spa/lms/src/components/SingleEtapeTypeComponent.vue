<template>
  <div>
    <h1>{{ rubrique.id }}</h1>
    <h3>{{ rubrique.text }}</h3>
    <img
      v-bind:src="rubrique.image_url ? rubrique.image_url : 'zqxm58jh0y.png'"
      alt=""
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      id: null,
      url: "/single_etape_type/",
      rubrique: {
        id: null,
        text: null,
        icon: null,
        image_url: null,
      },
    };
  },
  mounted() {
    this.id = this.$route.params.id;
    axios
      .get(this.url, { params: { id: this.id } })
      .then(
        (response) => (
          // this.rubrique = response.data.rubrique
          (this.rubrique.id = response.data.id),
          (this.rubrique.icon = response.data.icon),
          (this.rubrique.text = response.data.text),
          (this.rubrique.image_url = response.data.image_url)
        )
      )
      .catch((error) => console.log(error));
  },
  methods: {
    getLesson() {},
  },
};
</script>

<style></style>
