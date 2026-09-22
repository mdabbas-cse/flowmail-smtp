import { createRoot } from 'react-dom/client';
import { App } from './App';

const mount = document.getElementById('flowmail-smtp-app');
if (mount) createRoot(mount).render(<App slug={mount.dataset.page ?? 'flowmail-smtp'} />);
