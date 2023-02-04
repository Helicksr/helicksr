import { type AxiosInstance } from 'axios';
import { type Ziggy } from '~~/ziggy';
import 'vite/client';

declare global {
  interface Window {
    _: Lodash;
    axios: AxiosInstance;
    Ziggy: typeof Ziggy;
  }
}
