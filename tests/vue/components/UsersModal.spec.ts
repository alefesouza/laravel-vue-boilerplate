import Vue from 'vue';
import {
  shallow,
} from '@vue/test-utils';

import faker from 'faker';

import configStore from '../mocks/config-store';
import storeMock from '../mocks/store-mock';

import UsersModal from '@/components/UsersModal.vue';

storeMock.modules.Root.state = {};

describe('UsersModal.vue', () => {
  const store = configStore(Vue, storeMock);

  it('should render correctly', () => {
    const form = {
      name: faker.name.findName(),
    };

    const wrapper = shallow(UsersModal, {
      propsData: {
        form,
        modalData: {
          isAdd: false,
          okText: 'test',
        },
      },
    });

    expect(wrapper.find('b-modal').element.getAttribute('ok-title')).toEqual('test');
    expect(wrapper.find('b-modal').element.getAttribute('title')).toEqual(Vue.i18n.translate('users.edit_user', null));
  });
});
