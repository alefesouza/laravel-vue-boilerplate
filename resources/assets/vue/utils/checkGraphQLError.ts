const checkGraphQLError: any = (errors) => {
  if (errors) {
    return errors[Object.keys(errors)[0]][0];
  }

  return false;
};

export default checkGraphQLError;
