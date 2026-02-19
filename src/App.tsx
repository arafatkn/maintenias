import apiFetch from '@wordpress/api-fetch';
import { useEffect, useMemo, useRef, useState } from '@wordpress/element';

import { Header } from './components/Header';
import { Loader } from './components/Loader';
import { Index, type PageItem, type TemplateItem } from './pages/index';

declare global {
  interface Window {
    mainteniasData?: {
      restUrl: string;
      nonce: string;
    };
  }
}

type Settings = {
  enabled: boolean;
  pageId: number;
  selectionType: 'page' | 'template';
  templateSlug: string;
};

type SaveNotice = {
  type: 'idle' | 'saving' | 'success' | 'error';
  message: string;
};

const App = () => {
  const [pages, setPages] = useState<PageItem[]>([]);
  const [templates, setTemplates] = useState<TemplateItem[]>([]);
  const [settings, setSettings] = useState<Settings>({
    enabled: false,
    pageId: 0,
    selectionType: 'template',
    templateSlug: 'classic',
  });
  const [loading, setLoading] = useState(true);
  const [saveNotice, setSaveNotice] = useState<SaveNotice>({ type: 'idle', message: '' });
  const saveNoticeTimeoutRef = useRef<number | null>(null);

  const clearSaveNoticeTimeout = () => {
    if (saveNoticeTimeoutRef.current) {
      window.clearTimeout(saveNoticeTimeoutRef.current);
      saveNoticeTimeoutRef.current = null;
    }
  };

  useEffect(() => {
    // const fallbackRestUrl = `${window.location.origin}/wp-json/maintenias/v1/`;
    // apiFetch.use(apiFetch.createRootURLMiddleware(window.mainteniasData?.restUrl));

    if (window.mainteniasData?.nonce) {
      apiFetch.use(apiFetch.createNonceMiddleware(window.mainteniasData.nonce));
    }

    const bootstrap = async () => {
      try {
        const [fetchedPages, fetchedSettings, fetchedTemplates] = await Promise.all([
          apiFetch<PageItem[]>({ path: '/maintenias/v1/pages' }),
          apiFetch<Settings>({ path: '/maintenias/v1/settings' }),
          apiFetch<TemplateItem[]>({ path: '/maintenias/v1/templates' }),
        ]);

        setPages(fetchedPages);
        setSettings(fetchedSettings);
        setTemplates(fetchedTemplates);
      } finally {
        setLoading(false);
      }
    };

    bootstrap();

    return () => {
      if (saveNoticeTimeoutRef.current) {
        window.clearTimeout(saveNoticeTimeoutRef.current);
      }
    };
  }, []);

  const selectedPage = useMemo(() => pages.find((page) => page.id === settings.pageId), [pages, settings.pageId]);
  const selectedTemplate = useMemo(
    () => templates.find((t) => t.slug === settings.templateSlug),
    [templates, settings.templateSlug]
  );

  const persistSettings = async (nextSettings: Settings) => {
    clearSaveNoticeTimeout();
    setSettings(nextSettings);
    setSaveNotice({ type: 'saving', message: 'Saving...' });

    try {
      const updatedSettings = await apiFetch<Settings>({
        path: '/maintenias/v1/settings',
        method: 'POST',
        data: nextSettings,
      });

      setSettings(updatedSettings);
      setSaveNotice({ type: 'success', message: 'Saved successfully!' });
      saveNoticeTimeoutRef.current = window.setTimeout(() => {
        setSaveNotice({ type: 'idle', message: '' });
      }, 2500);
    } catch {
      setSaveNotice({ type: 'error', message: 'Failed to save. Please try again.' });
    }
  };

  const previewUrl =
    settings.selectionType === 'page' ? selectedPage?.permalink : selectedTemplate?.previewUrl;

  if (loading) {
    return <Loader height="60vh" />;
  }

  return (
    <>
      <div className="mt-5 mx-2 bg-white rounded-lg">
        <Header
          maintenanceMode={settings.enabled}
          onToggle={(enabled) => persistSettings({ ...settings, enabled })}
          previewUrl={previewUrl}
        />
        {settings.enabled && (
          <div className="mx-4 mt-4 rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">
            Maintenance mode is active. Logged-in administrators can still access the site normally.
          </div>
        )}
        <div className="p-4">
          <Index
            pages={pages}
            templates={templates}
            selectionType={settings.selectionType}
            selectedPageId={settings.pageId}
            selectedTemplateSlug={settings.templateSlug}
            onSelectPage={(pageId) => persistSettings({ ...settings, pageId, selectionType: 'page' })}
            onSelectTemplate={(slug) => persistSettings({ ...settings, templateSlug: slug, selectionType: 'template' })}
          />
        </div>
      </div>

      {saveNotice.type !== 'idle' && (
        <div
          className={`fixed top-12 right-4 z-50 max-w-sm rounded-lg px-4 py-3 text-sm shadow-lg ${
            saveNotice.type === 'saving'
              ? 'bg-white text-gray-700 border border-gray-200'
              : saveNotice.type === 'success'
                ? 'bg-green-600 text-white'
                : 'bg-red-600 text-white'
          }`}
          role="status"
          aria-live="polite"
        >
          {saveNotice.message}
        </div>
      )}
    </>
  );
};

export default App;
