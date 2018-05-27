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
      propsData: {
        form,
        modalData: {
          isAdd: false,
          okText: 'test',
        },
      },
    });

    expect(wrapper.find('.modal-footer .btn-primary').text()).toEqual('test');
    expect(wrapper.find('.modal-title').text())
      .toEqual(Vue.i18n.translate('users.edit_user', null));
  });
});
