<template>
  <k-field label="" class="fieldset" :data-blueprint="blueprint">
    <k-headline-field :label="label" />
    <k-line-field />
    <k-fieldset v-model="storedvalues" @input="onInput" :fields="fieldset" />
  </k-field>
</template>

<script>
export default {
  props: {
    fieldset: Object,
    storedvalues: Object,
    label: String,
    blueprint: String,
  },
  methods: {
    onInput(fieldsetValues) {
      // I don't know why, but have to rewrite fieldsetValues to store the values:
      var valuesObj = {};
      for (const [key, value] of Object.entries(fieldsetValues)) {
        valuesObj[key] = value;
      }      
      this.$emit("input", valuesObj);
    }
  }
};
</script>

<style lang="scss"></style>
