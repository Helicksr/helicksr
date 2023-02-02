/// <reference types="vite/client" />

import { AxiosInstance } from "axios";
import { Ziggy } from "~~/ziggy";

declare global {
  interface Window {
    _: Lodash,
    axios: AxiosInstance,
    Ziggy: typeof Ziggy,
  }
}

declare module "*.vue" {
  import { defineComponent } from "vue";
  const component: ReturnType<typeof defineComponent>;
  export default component;
}
