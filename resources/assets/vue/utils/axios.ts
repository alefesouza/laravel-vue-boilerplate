import axios from 'axios';

// Don't throw errors on 422 and 401 status code (used for validations)
axios.defaults.validateStatus = (status =>
  status === 422 ||
  status === 401 ||
  status >= 200 &&
  status < 300
);
