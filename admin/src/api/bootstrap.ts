import type { BootstrapData } from '../types';

export async function getBootstrap(config = window.FlowMailSMTPConfig): Promise<BootstrapData> {
  if (!config) throw new Error('FlowMail SMTP configuration is missing.');
  const response = await fetch(new URL('bootstrap', config.restUrl), {
    credentials: 'same-origin',
    headers: { 'X-WP-Nonce': config.nonce },
  });
  if (!response.ok) {
    throw new Error(response.status === 403 ? 'You do not have permission to view FlowMail SMTP.' : 'Could not load FlowMail SMTP data.');
  }
  const data: unknown = await response.json();
  if (!data || typeof data !== 'object' || !('version' in data) || typeof data.version !== 'string') {
    throw new Error('FlowMail SMTP returned an invalid response.');
  }
  return data as BootstrapData;
}
