import { DateTime } from "luxon";
import { App } from "vue";

export default {
  install: (app: App, options: any) => {
    app.config.globalProperties.$formatDatetime = (ISODate: string) => {
      return DateTime.fromISO(ISODate).toFormat('Y-m-d');
    }

    app.provide('datetime', options);
  }
}