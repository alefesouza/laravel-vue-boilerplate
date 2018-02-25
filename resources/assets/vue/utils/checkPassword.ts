import dialog from '@/utils/dialog';

const checkPassword = (values) => {
  if (values.password !== values.password_confirmation) {
    dialog('front.passwords_not_match', false);

    return false;
  }

  return true;
};

export default checkPassword;
