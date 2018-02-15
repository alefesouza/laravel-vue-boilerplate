const formValidation = (evt: Event) => {
  const loginForm = <HTMLFormElement>evt.target;

  if (!loginForm.checkValidity()) {
    return false;
  }

  evt.preventDefault();

  return true;
};

export default formValidation;
