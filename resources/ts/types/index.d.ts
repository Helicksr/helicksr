/// <reference types="vite/client" />

import { AxiosInstance } from "axios";
import { Config, InputParams, Router } from "ziggy-js";
import { Ziggy } from "~~/ziggy";

declare global {
  interface Window {
    _: Lodash,
    axios: AxiosInstance,
    Ziggy: typeof Ziggy,
  }
  
  declare function route(): Router;
  declare function route(name: string, params?: InputParams, absolute?: boolean, customZiggy?: Config): string;
}

declare module "*.vue" {
  import { defineComponent } from "vue";
  const component: ReturnType<typeof defineComponent>;
  export default component;
}
