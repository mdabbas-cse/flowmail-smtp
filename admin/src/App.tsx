import { useEffect, useState } from 'react';
import { getBootstrap } from './api/bootstrap';
import { pageFromSlug, pages } from './navigation';
import type { BootstrapData, Page } from './types';
import { ProvidersPage } from './pages/ProvidersPage';
import { SettingsPage } from './pages/SettingsPage';
import { TestEmailPage } from './pages/TestEmailPage';
import './admin.css';

function useBootstrap() {
  const [data, setData] = useState<BootstrapData | null>(null);
  const [error, setError] = useState('');
  useEffect(() => {
    let active = true;
    getBootstrap().then((value) => { if (active) setData(value); }).catch((reason: unknown) => {
      if (active) setError(reason instanceof Error ? reason.message : 'Could not load FlowMail SMTP data.');
    });
    return () => { active = false; };
  }, []);
  return { data, error };
}

function Placeholder({ title, description }: { title: string; description: string }) {
  return <div className="flowmail-smtp-card"><h2>{title}</h2><p>{description}</p><span className="flowmail-smtp-pill">Coming in a later phase</span></div>;
}

function Content({ page }: { page: Page }) {
  if (page === 'mailers') return <ProvidersPage />;
  if (page === 'logs') return <Placeholder title="Mail Logs" description="Mail logging will be added in a later phase." />;
  if (page === 'settings') return <SettingsPage />;
  return <TestEmailPage />;
}

export function App({ slug }: { slug: string }) {
  const page = pageFromSlug(slug);
  const { data, error } = useBootstrap();
  const heading = pages.find((item) => item.id === page)?.label ?? 'Dashboard';
  return <div className="flowmail-smtp-admin">
    <header className="flowmail-smtp-header"><div><h1>FlowMail SMTP</h1><p>Email delivery and activity for this WordPress site</p></div>{data && <span>Version {data.version}</span>}</header>
    <nav className="flowmail-smtp-nav" aria-label="FlowMail SMTP pages">{pages.map((item) => <a key={item.id} aria-current={page === item.id ? 'page' : undefined} href={`admin.php?page=${item.slug}`}>{item.label}</a>)}</nav>
    <main><h2 className="flowmail-smtp-page-title">{heading}</h2>
      {error && <div className="notice notice-error" role="alert"><p>{error}</p></div>}
      {!data && !error && <p role="status">Loading FlowMail SMTP…</p>}
      {data && <Content page={page} />}
    </main>
  </div>;
}
