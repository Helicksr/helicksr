import type { User } from './models';

export {};

declare module '@inertiajs/core' {
  export interface PageProps {
    jetstream: {
      canCreateTeams: boolean;
      canManageTwoFactorAuthentication: boolean;
      canUpdatePassword: boolean;
      canUpdateProfileInformation: boolean;
      hasEmailVerification: boolean;
      flash: {
        bannerStyle?: 'success' | 'danger';
        banner?: string;
        token?: string;
      };
      hasAccountDeletionFeatures: boolean;
      hasApiFeatures: boolean;
      hasTeamFeatures: boolean;
      hasTermsAndPrivacyPolicyFeature: boolean;
      managesProfilePhotos: boolean;
    };
    auth: {
      user: User;
    };
  }
}
