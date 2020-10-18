<template>
  <!-- not sure what to use, form or fieldset -->
  <!-- <k-form v-model="storedvalues" @input="onInput" :fields="fieldsetFields" /> -->
  <k-field label="" class="fieldset" :data-blueprint="blueprint">
    <k-headline-field :label="label" />
    <k-line-field />
    <k-fieldset :fields="fieldsetFields" v-model="storedvalues" @input="onInput" />
  </k-field>
</template>

<script>
import Vue from "vue";
export default {
  props: {
    fieldset: Object,
    storedvalues: Object,
    label: String,
    blueprint: String,
    name: String,
  },
  computed: {
    fieldsetFields() {
      // build endpoints for the fields..
      // isn't there an internal kirby function to do this?
      let fields = {};
      Object.keys(this.fieldset).forEach(name => {
        let field = this.fieldset[name];
        field.section = this.name;
        var ep = this.$attrs.endpoints;
        field.endpoints = {
          field: `${ep.model}/fields/${field.section}+${name}`,
          model: ep.model,
          section: ep.section,
        };
        fields[name] = field;
      });

      return fields;
    },
  },
  methods: {
    onInput(fieldsetValues) {
      // I don't know why, but have to cleanup the values to store them sanely:
      // isn't there some internal function to store or sanitize a form?
      // afaik files & links return a huge array that has to be cleaned
      var cleanedValues = {};
      for (const [key, value] of Object.entries(fieldsetValues)) {
        if(typeof value === 'object') {
          // value is an object of some kind
          var objvals = {};
          for (const [fkey, fvalue] of Object.entries(value)) {
            // cleanup file field entry
            if (fvalue['filename']) {
              objvals[fkey] = fvalue['filename'];
            } 
            // cleanup link field entry
            else if (fvalue['link']) {
              objvals[fkey] =  fvalue['id'];
            }
            // everything else seems fine..
            else {
              objvals[fkey] = fvalue; 
            }
          }
          cleanedValues[key] = objvals;
        } else {
          // this is just a string value
          cleanedValues[key] = value;
        }
      }  
      this.$emit("input", cleanedValues);
    }
  }
};
</script>

<style lang="scss">
  
</style>
