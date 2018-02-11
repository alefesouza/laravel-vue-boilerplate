import Vue from 'vue';

const translate = (key: string, options?: any): string => {
  return <string>Vue.i18n.translate(key, options);
};

export default translate;
