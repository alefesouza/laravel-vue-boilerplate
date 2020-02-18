import Vue from 'vue';

import {
  CardPlugin,
  ButtonPlugin,
  ModalPlugin,
  FormPlugin,
  ListGroupPlugin,
  NavbarPlugin,
  BContainer,
  BRow,
  BCol,
  BBadge,
  BLink,
  BFormSelect,
  BFormInput,
  BPagination,
  BFormGroup,
  BFormTextarea,
  BFormFile,
  BFormCheckbox,
  BFormRadioGroup,
  BFormRadio,
  BDropdown,
  BDropdownItem,
} from 'bootstrap-vue';

Vue.component('b-dropdown', BDropdown);
Vue.component('b-dropdown-item', BDropdownItem);

Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(ModalPlugin);
Vue.use(FormPlugin);
Vue.use(ListGroupPlugin);
Vue.use(NavbarPlugin);
Vue.component('b-container', BContainer);
Vue.component('b-row', BRow);
Vue.component('b-col', BCol);
Vue.component('b-badge', BBadge);
Vue.component('b-link', BLink);
Vue.component('b-form-input', BFormInput);
Vue.component('b-form-select', BFormSelect);
Vue.component('b-form-group', BFormGroup);
Vue.component('b-form-textarea', BFormTextarea);
Vue.component('b-form-file', BFormFile);
Vue.component('b-form-checkbox', BFormCheckbox);
Vue.component('b-form-radio-group', BFormRadioGroup);
Vue.component('b-form-radio', BFormRadio);
Vue.component('b-pagination', BPagination);
