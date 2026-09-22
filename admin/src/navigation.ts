import type { Page } from './types';

export const pages: { id: Page; label: string; slug: string }[] = [
  { id: 'dashboard', label: 'Dashboard', slug: 'flowmail-smtp' },
  { id: 'mailers', label: 'Providers', slug: 'flowmail-smtp-mailers' },
  { id: 'logs', label: 'Mail Logs', slug: 'flowmail-smtp-logs' },
  { id: 'settings', label: 'Settings', slug: 'flowmail-smtp-settings' },
];

export function pageFromSlug(slug: string): Page {
  return pages.find((page) => page.slug === slug)?.id ?? 'dashboard';
}
