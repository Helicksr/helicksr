/// <reference types="vite/client" />
import type { Axios } from 'axios';

declare global {
  interface Window {
    axios: Axios;
    Ziggy: {
      url: string,
      port: number | null,
      defaults: any,
      routes: any,
    };
  }
};


