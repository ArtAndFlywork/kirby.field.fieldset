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
    onInput(value) {
      console.log('fieldset fields changed, updating');
      this.$emit("input", value);
    }
  }
};
</script>

<style lang="scss"></style>
