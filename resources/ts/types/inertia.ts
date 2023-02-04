import type { Team, TeamMembership, User } from './models';

export {};

declare module '@inertiajs/inertia' {
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
    user: User & {
      all_teams?: Team[] | null;
      current_team?: Team;
      two_factor_enabled: boolean;
      membership?: TeamMembership;
    };
  }
}
