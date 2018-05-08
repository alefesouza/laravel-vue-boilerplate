import dialog from '@/utils/dialog';
import { find } from 'lodash';

const checkResponse = ({ status, data }) => {
  if (status !== 200 || data.errors) {
    dialog(find(data.errors)[0], false);

    return true;
  }

  return false;
};

export default checkResponse;
