<template>
  <!-- <k-form v-model="contact" @input="onInput" :fields="fieldsetFields" /> -->
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
    name: String,
  },
  computed: {
    fieldsetFields() {
      let fields = {};
      Object.keys(this.fieldset).forEach(name => {
        let field = this.fieldset[name];
        field.section = this.name;
        var ep = this.$attrs.endpoints;        
        field.endpoints = {
          field: `${ep.model}/fields/${field.section}+${name}`,
          model: ep.model,
          section: ep.field,
        };
        fields[name] = field;
      });

      return fields;
    },
  },
  methods: {
    onInput(fieldsetValues) {
      // console.log(fieldsetValues);
      // I don't know why, but have to rewrite fieldsetValues to store the values:
      var valuesObj = {};
      for (const [key, value] of Object.entries(fieldsetValues)) {
        if(typeof value === 'object') {
          for (const [fkey, fvalue] of Object.entries(value)) {
            console.log(fkey);
            console.log(fvalue);
            if (fvalue['filename']) {
              console.log(fvalue['filename']);
              valuesObj[key] = [fvalue['filename']];
            } 
            else if (fvalue['link']) {
              valuesObj[key] = [fvalue['link']];
            }
          }
        } else {
          valuesObj[key] = value;
        }
      }  
      this.$emit("input", valuesObj);
    }
  }
};
</script>

<style lang="scss"></style>
