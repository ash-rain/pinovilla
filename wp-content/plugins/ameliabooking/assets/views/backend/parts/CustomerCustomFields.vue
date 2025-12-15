<template>
  <div class="am-dialog-table am-custom-fields-container">

    <!-- Form -->
    <div class="am-customer-extras">

      <!-- Custom Fields -->
      <div class="am-custom-fields">
        <el-form-item
            v-if="customField.type !== 'content'"
            v-for="(customField, key) in customFields"
            :key="customField.id"
            :label="(customField.type !== 'content' && customField.type !== 'checkbox' && customField.type !== 'radio') && customField.label ? customField.label + ':' : ''"
        >
          <span
              v-if="(customField.type === 'checkbox' || customField.type === 'radio') && customField.label" v-html="customField.label ? '<label class=' + '\'el-form-item__label\'>' + customField.label + '</label>' + ':' : ''"
              class="">
          >
          </span>

          <!-- Text Field -->
          <el-input
              v-if="customField.type === 'text'"
              v-model="customer.customFields[customField.id].value"
              placeholder=""
              @input="clearValidation()"
          >
          </el-input>

          <!-- Address Field -->
          <div class="el-input" v-if="customField.type === 'address'" :style="{marginBottom: $root.settings.general.gMapApiKey ? '':0}">
            <vue-google-autocomplete
                v-if="googleMapsLoaded()"
                :id="'address-autocomplete-'+key+'-'+customField.id"
                classname="el-input__inner"
                :value="customer.customFields[customField.id].value"
                @input="customer.customFields[customField.id].value = $event"
                @change="setAddressCF($event, customField.id)"
                placeholder=""
                v-on:placechanged="(address) => getAddressData(address, customField.id)"
                types=""
            >
            </vue-google-autocomplete>
            <el-input
                v-else
                placeholder=""
                v-model="customer.customFields[customField.id].value"
            >
            </el-input>
          </div>
          <!-- /Address Field -->


          <!-- Text Area -->
          <el-input
              v-else-if="customField.type === 'text-area'"
              v-model="customer.customFields[customField.id].value"
              :rows="3"
              class="am-front-texarea"
              placeholder=""
              type="textarea"
              @input="clearValidation()"
          >
          </el-input>

          <!-- Selectbox -->
          <el-select
              v-else-if="customField.type === 'select'"
              v-model="customer.customFields[customField.id].value"
              placeholder=""
              clearable
              :popper-class="'am-dropdown-cabinet'"
              @change="clearValidation()"
          >
            <el-option
                v-for="(option, index) in getCustomFieldOptions(customField.options)"
                :key="index"
                :value="option"
                :label="option"
            >
            </el-option>
          </el-select>

          <!-- Checkbox -->
          <el-checkbox-group
              v-else-if="customField.type === 'checkbox'"
              v-model="customer.customFields[customField.id].value"
              aria-required="false"
              @change="clearValidation()"
          >
            <el-checkbox
                v-for="(option, index) in getCustomFieldOptions(customField.options)"
                :key="index"
                :label="option"
            >
            </el-checkbox>
          </el-checkbox-group>

          <!-- Radio Buttons -->
          <el-radio-group
              v-else-if="customField.type === 'radio'"
              v-model="customer.customFields[customField.id].value"
          >
            <el-radio
                v-for="(option, index) in getCustomFieldOptions(customField.options)"
                :key="index"
                :label="option"
                @change="clearValidation()"
            >
            </el-radio>
          </el-radio-group>

          <!-- Uploaded Files -->
          <div v-else-if="customField.type === 'file' && hideAttachmentCustomField === false" v-for="(fileInfo, index) in customer.customFields[customField.id].value" style="margin: 15px; clear: left">
            <a
                :key="index"
                :href="$root.useUploadsAmeliaPath ? $root.getAjaxUrl + '/fields/' + customField.id + '/' + customer.id + '/' + index + (isCabinet ? '&source=cabinet-provider' : '') : $root.getUploadsAmeliaUrl + customer.id + '_' + fileInfo.fileName"
                target="_blank"
            >
              {{fileInfo.name}}
            </a>
          </div>

          <!-- Date picker -->
          <div v-else-if="customField.type === 'datepicker'">
            <v-date-picker
                v-model="customer.customFields[customField.id].value"
                mode='single'
                popover-visibility="focus"
                popover-direction="bottom"
                :popover-align="screenWidth < 768 ? 'center' : 'left'"
                :tint-color='"#1A84EE"'
                :show-day-popover=false
                :input-props='{class: "el-input__inner", readOnly: "readonly"}'
                :is-expanded=false
                :is-required=true
                :disabled=false
                :formats="vCalendarFormats"
            />
          </div>

        </el-form-item>
      </div>

    </div>
  </div>
</template>

<script>
import customFieldMixin from '../../../js/common/mixins/customFieldMixin'
import dateMixin from '../../../js/common/mixins/dateMixin'
import windowMixin from '../../../js/backend/mixins/windowMixin'
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import helperMixin from '../../../js/backend/mixins/helperMixin'
import phoneCountriesMixin from '../../../js/common/mixins/phoneCountriesMixin'

export default {
  mixins: [customFieldMixin, dateMixin, windowMixin, helperMixin, phoneCountriesMixin],

  props: {
    entityId: null,
    entityType: null,
    customer: {
      default: () => {}
    },
    customFields: {
      default: () => []
    },
    hideAttachmentCustomField: {
      required: false,
      default: false,
      type: Boolean
    },
    isCabinet: {
      type: Boolean,
      default: false,
      required: false
    }
  },

  created () {
    this.setInitCustomerCustomFields()

    for (let key in this.customer.customFields) {
      if (this.customer.customFields[key].type === 'datepicker' && this.customer.customFields[key].value) {
        this.customer.customFields[key].value = this.$moment(this.customer.customFields[key].value).toDate()
      }
    }
  },

  methods: {
    setAddressCF (input, cfId) {
      this.customer.customFields[cfId].value = input
    },

    getAddressData: function (addressComponents, cfId) {
      if (addressComponents && this.customer.customFields[cfId]) {
        this.customer.customFields[cfId].components = this.mapAddressComponentsForXML(addressComponents)
        const country = this.countries.find(c => c.nicename === this.customer.customFields[cfId].components.CountryCode)
        if (country) {
          this.customer.customFields[cfId].components.CountryCode = country.iso
        }
      }
    },

    googleMapsLoaded () {
      return window.google && this.$root.settings.general.gMapApiKey
    },

    clearValidation () {
      this.$emit('clearValidation')
    },

    setInitCustomerCustomFields () {
      let customField = this.customer.customFields ? this.customer.customFields : {}
      this.customFields.forEach((cf) => {
        if (customField[cf.id] === undefined) {
          customField[cf.id] = {
            value: (cf.type === 'checkbox' || cf.type === 'attachment' || cf.type === 'radio') ? [] : '',
            type: cf.type,
            label: cf.label
          }
        }
      })

      this.customer.customFields = customField
    }
  },

  components: {
    VueGoogleAutocomplete
  }
}
</script>
