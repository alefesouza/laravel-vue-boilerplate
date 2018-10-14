const checkResponse: any = ({ status, data }) => {
  if ((status < 200 || status >= 300) || data.errors) {
    // Is there a best way to get Laravel errors message without libs?
    return {
      message: data.errors[Object.keys(data.errors)[0]][0],
    };
  }

  return false;
};

export default checkResponse;
