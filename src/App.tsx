import apiFetch from '@wordpress/api-fetch';
import { useEffect, useMemo, useState } from '@wordpress/element';

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
  }, []);

  const selectedPage = useMemo(() => pages.find((page) => page.id === settings.pageId), [pages, settings.pageId]);

  const persistSettings = async (nextSettings: Settings) => {
    setSettings(nextSettings);
    await apiFetch<Settings>({
      path: '/maintenias/v1/settings',
      method: 'POST',
      data: nextSettings,
    });
  };

  const previewUrl = settings.selectionType === 'page' ? selectedPage?.permalink : undefined;

  if (loading) {
    return <Loader height="60vh" />;
  }

  return (
    <div className="mt-5 mx-2 bg-white rounded-lg">
      <Header
        maintenanceMode={settings.enabled}
        onToggle={(enabled) => persistSettings({ ...settings, enabled })}
        previewUrl={previewUrl}
      />
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
  );
};

export default App;
