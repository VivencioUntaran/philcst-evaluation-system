
import axios from "axios";

export const useApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiURL

  return axios.create({
      baseURL,
      headers: {},
  });
}