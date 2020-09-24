<template>
  <k-field label="" class="fieldset" :data-blueprint="blueprint">
    <k-headline-field :label="label" />
    <k-line-field />
    <k-fieldset :fields="fieldsetFields" v-model="storedvalues" @input="onInput" />
  </k-field>
</template>

<script>
export default {
  props: {
    fieldset: Object,
    storedvalues: Object,
    label: String,
    blueprint: String,
    name: String
  },
  computed: {
    fieldsetFields() {
      let fields = {};
      Object.keys(this.fieldset).forEach(name => {
        let field = this.fieldset[name];
        field.section = this.name;
        var ep = this.$attrs.endpoints;        
        field.endpoints = {
          field: `fieldset/${ep.model}/fields/${field.section}+${name}`,
          model: ep.model,
          section: ep.field,
        };

        // for reference: 🕵️‍♂️
        // var thisIsWhatKirbyBuilderDoes = {
        //   field: "kirby-builder/pages/testpagina-1/fields/bld+mwep+hello",
        //   model: "pages/testpagina-1",
        //   section: "pages/testpagina-1/sections/body",
        // };

        fields[name] = field;
      });

      return fields;
    },
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
