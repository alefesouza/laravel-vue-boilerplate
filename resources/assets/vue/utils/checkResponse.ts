import dialog from '@/utils/dialog';

const checkResponse = ({ status, data }) => {
  if ((status < 200 || status >= 300) || data.errors) {
    // Is there a best way to get Laravel errors message without libs?
    dialog(data.errors[Object.keys(data.errors)[0]][0], false);

    return true;
  }

  return false;
};

export default checkResponse;
