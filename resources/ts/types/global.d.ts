/// <reference types="vite/client" />

import { type AxiosInstance } from 'axios';
import { type Ziggy } from '~~/ziggy';

declare global {
  interface Window {
    _: Lodash;
    axios: AxiosInstance;
    Ziggy: typeof Ziggy;
  }
}
