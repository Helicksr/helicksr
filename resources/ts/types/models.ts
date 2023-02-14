export interface ApiToken {
  id: number;
  name: string;
  abilities: CRUDPermissions[];
  last_used_ago: string | null;
  last_used_at: string | null;
  expires_at: string | null;
  created_at: string | null;
  updated_at: string | null;
  tokeneable_id: number;
  tokeneable_type: string;
}

export interface BrowserSession {
  agent: {
    is_desktop: boolean;
    platform: string;
    browser: string;
  };
  ip_address: string;
  is_current_device: boolean;
  last_active: string;
}

export interface AmpSetting {
  knob: string;
  value: string | null;
}

export interface Lick {
  id: number;
  transcription: string;
  title: string;
  tempo: number;
  amp_settings: AmpSetting[];
  audio_file_path: string | null;
  audio_file_url: string | null;
  length: number;
  tags: string[];
  user_id: number;
  created_at: string;
  updated_at: string;
}

export interface Team {
  id: number;
  user_id: number;
  name: string;
  personal_team: boolean;
  owner: User;
  team_invitations: TeamInvitation[];
  users: User[];
  created_at: string;
  updated_at: string;
}

export interface TeamMembership {
  team_id: number;
  user_id: number;
  role: string;
  created_at: string;
  updated_at: string;
}

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at: string | null;
  notification_settings: {
    on_lick_shared: boolean;
    on_added_to_group: boolean;
  };
  current_team_id: number | null;
  membership: TeamMembership;
  profile_photo_path: string | null;
  created_at: string | null;
  updated_at: string | null;
  two_factor_confirmed_at: string | null;
  profile_photo_url: string;
}

export interface UserSession {
  agent: {
    browser: string;
    is_desktop: boolean;
    platform: string;
  };
  ip_address: string;
  is_current_device: boolean;
  last_active: string;
}

export interface TeamInvitation {
  id: number;
  team_id: number;
  email: string;
  role: string;
  created_at: string | null;
  updated_at: string | null;
}

export interface UserPermissions {
  canAddTeamMembers: boolean;
  canDeleteTeam: boolean;
  canRemoveTeamMembers: boolean;
  canUpdateTeam: boolean;
}

export type CRUDPermissions = Array<'create' | 'read' | 'update' | 'delete'>;

export interface Role {
  description: string;
  key: string;
  name: string;
  permissions: CRUDPermissions[];
}
