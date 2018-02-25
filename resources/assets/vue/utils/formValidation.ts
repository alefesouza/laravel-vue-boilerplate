const formValidation = (evt: Event) => {
  const form = <HTMLFormElement>evt.target;

  if (!form.checkValidity()) {
    return false;
  }

  evt.preventDefault();

  return true;
};

export default formValidation;
