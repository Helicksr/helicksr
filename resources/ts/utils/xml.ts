import { XMLValidator } from 'fast-xml-parser';

export const isValidXml = (subject: string): boolean => {
  const result = XMLValidator.validate(subject);

  return result === true;
};
