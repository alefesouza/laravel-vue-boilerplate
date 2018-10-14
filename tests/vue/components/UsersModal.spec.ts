import Vue from 'vue';
import {
  mount,
} from '@vue/test-utils';

import faker from 'faker';

import configStore from '../mocks/config-store';
import storeMock from '../mocks/store-mock';

import UsersModal from '@/components/UsersModal.vue';

storeMock.state = {};

describe('UsersModal.vue', () => {
  const store = configStore(Vue, storeMock);

  it('should render correctly', () => {
    const form = {
      name: faker.name.findName(),
    };

    const wrapper = mount(UsersModal, {
      store,
      propsData: {
        form,
        isAdd: false,
        isVisible: true,
      },
    });

    expect((<HTMLInputElement>wrapper.find('#name').element).value)
      .toEqual(form.name);
    expect(wrapper.find('.modal-footer .btn-primary').text())
      .toEqual(Vue.i18n.translate('buttons.update', null));
    expect(wrapper.find('.modal-title').text())
      .toEqual(Vue.i18n.translate('users.edit_user', null));
  });
});
