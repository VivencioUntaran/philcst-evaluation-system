// https://nuxt.com/docs/api/configuration/nuxt-config
export default {
  css: [
    '~/node_modules/bootstrap/dist/css/bootstrap.css',
    '~/assets/css/global.css',
  ],
  target: 'static',
  meta: [
    { charset: 'utf-8' },
    { name: 'viewport', content: 'width=device-width, initial-scale=1' }
  ],
  plugins: [
    { src: '~/plugins/bootstrap.js', mode: 'client' },
    { src: '~/plugins/simplehtml2pdf.js', ssr: false },
  ],

  modules: ['nuxt-icon'],
  //  'nuxt-vue-multiselect'
  axios: {
    baseURL: process.env.API_URL
  },
  runtimeConfig: {
    public: {
      apiURL: process.env.API_URL,
    }
  },
};
